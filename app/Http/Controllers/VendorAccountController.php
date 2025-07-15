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
        // Eager load vendor.ledgers for performance to calculate available_advance efficiently.
        $unpaidBills = VendorAccount::with(['vendor.ledgers', 'purchase'])
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->latest('id')
            ->paginate(10);

        return view('vendor_accounts.index', compact('unpaidBills'));
    }

    /**
     * Correctly processes payments. Advance Adjustment is used for calculation only.
     */
    public function processPayment(Request $request, VendorAccount $vendor_account)
    {
        // --- 1. Simplified Validation ---
        $currentBalance = $vendor_account->balance;

        $validated = $request->validate([
            'new_payment' => ['nullable', 'numeric', 'min:0'],
            // *** THIS IS THE KEY CHANGE ***
            // We still validate 'advance_adjustment' to ensure it's a valid number,
            // but we REMOVE the 'max' rule. It is now just a calculator field.
            'advance_adjustment' => ['nullable', 'numeric', 'min:0'],
            
            'payment_type' => ['nullable', 'required_if:new_payment,>,0', 'string', 'in:Cash,Bank Transfer,Cheque'],
            'notes' => ['nullable', 'string', 'max:500'],
            'due_payment' => ['required', 'numeric', 'min:0'],
        ], [
            // We no longer need the 'advance_adjustment.max' custom message.
            'payment_type.required_if' => 'The Payment Type field is required when making a new payment.',
        ]);

        // Get the individual components and the total payment from the validated data
        $newPayment = (float)($validated['new_payment'] ?? 0);
        $advanceAdjustment = (float)($validated['advance_adjustment'] ?? 0);
        $totalPayment = (float)($validated['due_payment'] ?? 0);

        // Security check: Ensure frontend calculation matches backend to prevent tampering.
        if (abs($totalPayment - ($newPayment + $advanceAdjustment)) > 0.01) {
            return back()->withErrors(['payment_error' => 'Payment calculation mismatch. Please refresh and try again.'])->withInput();
        }

        // Business logic checks
        if ($totalPayment <= 0) {
            return back()->withErrors(['payment_error' => 'You must enter a payment amount.'])->withInput();
        }

        if ($totalPayment > round($currentBalance, 2) + 0.01) { // Add a small tolerance
            return back()->withErrors(['payment_error' => 'Total payment cannot exceed the current balance.'])->withInput();
        }

        // --- 2. Database Transaction ---
        DB::transaction(function () use ($vendor_account, $newPayment, $totalPayment, $request) {
            $purchase = $vendor_account->purchase;
            $notes = $request->notes ?? null;
            $purchase_no = $purchase ? $purchase->purchase_no : 'Bill';

            // ONLY the "New Payment" value creates a new ledger entry.
            if ($newPayment > 0) {
                VendorLedger::create([
                    'vendor_id' => $vendor_account->vendor_id,
                    'purchase_id' => $vendor_account->purchase_id,
                    'transaction_date' => now(),
                    'description' => 'Payment for ' . $purchase_no,
                    'received_by' => Auth::user()->name,
                    'payment_type' => $request->payment_type,
                    'notes' => $notes,
                    'debit' => $newPayment,
                    'credit' => 0,
                ]);
            }
            
            // The "Total Payment" value updates the VendorAccount.
            $vendor_account->paid_amount += $totalPayment;

            // Update the status of the bill
            if ($vendor_account->balance <= 0.009) {
                $vendor_account->paid_amount = $vendor_account->amount;
                $vendor_account->status = 'paid';
                $vendor_account->paid_date = now();
                if ($purchase) $purchase->update(['status' => 'paid']);
            } else {
                $vendor_account->status = 'partially_paid';
            }
            
            $vendor_account->save();
        });

        return redirect()->route('vendor_accounts.index')->with('success', 'Payment of ' . number_format($totalPayment, 2) . ' processed successfully.');
    }
}