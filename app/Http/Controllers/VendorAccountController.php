<?php

namespace App\Http\Controllers;

use App\Models\VendorAccount;
use App\Models\VendorLedger;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VendorAccountController extends Controller
{
    /**
     * Display the list of unpaid and partially paid bills.
     */
    public function index()
    {
        $unpaidBills = VendorAccount::with(['vendor', 'purchase'])
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->latest('id')
            ->paginate(10);

        return view('vendor_accounts.index', compact('unpaidBills'));
    }

    /**
     * FINAL GUARANTEED METHOD:
     * This version includes the critical validation fix that prevents over-applying an advance
     * and uses the standard, transparent accounting method.
     */
    public function processPayment(Request $request, VendorAccount $vendor_account)
    {
        // --- 1. Get Balances ---
        $vendor = $vendor_account->vendor;
        $currentBalance = $vendor_account->balance;
        $availableAdvance = $vendor->available_advance;

        // --- 2. CRITICAL FIX: Calculate the real maximum advance for THIS bill ---
        // The most advance you can apply is the lesser of the bill's balance or the total advance available.
        $maxAdvanceForThisBill = min($currentBalance, $availableAdvance);

        // --- 3. Validation with the NEW, correct maximum ---
        $validated = $request->validate([
            'due_payment' => ['nullable', 'numeric', 'min:0'],
            'advance_adjustment' => ['nullable', 'numeric', 'min:0', 'max:' . $maxAdvanceForThisBill],
            'payment_type' => ['required_if:due_payment,>,0', 'string', 'in:Cash,Bank Transfer,Cheque'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            // Custom error message for the new validation rule
            'advance_adjustment.max' => 'Advance adjustment cannot exceed the bill balance or available advance (' . number_format($maxAdvanceForThisBill, 2) . ').',
        ]);

        $duePayment = (float)($validated['due_payment'] ?? 0);
        $advanceAdjustment = (float)($validated['advance_adjustment'] ?? 0);
        $totalPayment = $duePayment + $advanceAdjustment;

        // Final check for overpayment (this will now catch any manual errors)
        if ($totalPayment > round($currentBalance, 2) + 0.01) {
            return back()->withErrors(['payment_error' => 'Total payment (' . number_format($totalPayment, 2) . ') cannot exceed the current bill balance of ' . number_format($currentBalance, 2) . '.'])->withInput();
        }

        if ($totalPayment <= 0) {
            return back()->withErrors(['payment_error' => 'You must enter a payment amount.'])->withInput();
        }

        // --- 4. Database Transaction ---
        DB::transaction(function () use ($vendor_account, $duePayment, $advanceAdjustment, $request, $totalPayment) {
            $purchase = $vendor_account->purchase;
            $notes = $request->notes ?? null;
            $purchase_no = $purchase ? $purchase->purchase_no : 'Bill';

            // Entry 1: Create a debit for the new money received
            if ($duePayment > 0) {
                VendorLedger::create([
                    'vendor_id' => $vendor_account->vendor_id,
                    'purchase_id' => $vendor_account->purchase_id,
                    'transaction_date' => now(),
                    'description' => 'Payment for ' . $purchase_no,
                    'received_by' => Auth::user()->name,
                    'payment_type' => $request->payment_type,
                    'notes' => $notes,
                    'debit' => $duePayment,
                    'credit' => 0,
                ]);
            }

            // Entry 2: Create a separate debit for the advance being used
            if ($advanceAdjustment > 0) {
                VendorLedger::create([
                    'vendor_id' => $vendor_account->vendor_id,
                    'purchase_id' => $vendor_account->purchase_id,
                    'transaction_date' => now(),
                    'description' => 'Payment via advance for ' . $purchase_no,
                    'received_by' => Auth::user()->name,
                    'payment_type' => 'Advance Adjustment',
                    'notes' => 'Applied from available advance balance.',
                    'debit' => $advanceAdjustment,
                    'credit' => 0,
                ]);
            }

            // Update the master bill record
            $vendor_account->paid_amount += $totalPayment;
            if ($vendor_account->balance <= 0) {
                $vendor_account->status = 'paid';
                $vendor_account->paid_date = now();
                if ($purchase) {
                    $purchase->update(['status' => 'paid']);
                }
            } else {
                $vendor_account->status = 'partially_paid';
            }
            $vendor_account->save();
        });

        return redirect()->route('vendor_accounts.index')->with('success', 'Payment of ' . number_format($totalPayment, 2) . ' processed successfully.');
    }
}