<?php

namespace App\Http\Controllers;

use App\Models\CustomerAccount;
use App\Models\CustomerLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Make sure to import Str
use Illuminate\Support\Facades\Validator; // Add this
use Illuminate\Validation\Rule; // Add this


class CustomerAccountController extends Controller
{
    // ... your index() method remains the same ...
    public function index()
    {
        $unpaidBills = CustomerAccount::with(['sale', 'customer.ledgers']) // <-- MODIFIED HERE
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->latest('id')
            ->paginate(10);

        return view('customer_accounts.index', compact('unpaidBills'));
    }
    /**
     * Processes a payment from the main due bills list.
     * Creates detailed ledger entries for new payments and advance adjustments.
     */
    public function processPayment(Request $request, CustomerAccount $customer_account)
    {
        // --- 1. Base Validation ---
        $request->validate([
            'transaction_type' => ['required', Rule::in(['new_payment', 'advance_adjustment', 'vat_adjustment', 'tax_adjustment', 'other_adjustment'])],
        ]);

        $type = $request->input('transaction_type');
        $customer = $customer_account->customer;

        // --- 2. Dynamic Validation & Processing within a DB Transaction ---
        try {
            DB::transaction(function () use ($request, $type, $customer_account, $customer) {

                $transactionId = strtoupper(substr($type, 0, 3)) . '-' . strtoupper(Str::random(8));

                switch ($type) {
                    case 'new_payment':
                        $validated = Validator::make($request->all(), [
                            'payment_amount' => 'required|numeric|min:0.01',
                            'payment_type' => 'required|string',
                        ])->validate();

                        CustomerLedger::create([
                            'customer_id' => $customer_account->customer_id,
                            'sale_id' => $customer_account->sale_id,
                            'transaction_id' => $transactionId,
                            'transaction_type' => $type,
                            'transaction_date' => now(),
                            'description' => 'Payment for PO: ' . $customer_account->sale->bill_no,
                            'debit' => 0,
                            'credit' => $validated['payment_amount'],
                            'payment_type' => $validated['payment_type'],
                            'received_by' => Auth::user()->name,
                        ]);
                        $customer_account->increment('paid_amount', $validated['payment_amount']);
                        break;

                    case 'advance_adjustment':
                        $validated = Validator::make($request->all(), [
                            'advance_amount' => 'required|numeric|min:0.01|max:' . $customer->available_advance,
                        ])->validate();

                        CustomerLedger::create([
                            'customer_id' => $customer_account->customer_id,
                            'sale_id' => $customer_account->sale_id,
                            'transaction_id' => $transactionId,
                            'transaction_type' => $type,
                            'transaction_date' => now(),
                            'description' => 'Advance adjusted for PO: ' . $customer_account->sale->bill_no,
                            'debit' => 0,
                            'credit' => $validated['advance_amount'],
                            'received_by' => Auth::user()->name,
                        ]);
                        $customer_account->increment('paid_amount', $validated['advance_amount']);
                        break;

                    case 'vat_adjustment':
                    case 'tax_adjustment':
                    case 'other_adjustment':
                        $validated = Validator::make($request->all(), [
                            'adjustment_amount' => 'required|numeric|min:0.01',
                            'effect' => ['required', Rule::in(['debit', 'credit'])],
                            'notes' => 'required|string|max:255',
                        ])->validate();

                        $debit = ($validated['effect'] === 'debit') ? $validated['adjustment_amount'] : 0;
                        $credit = ($validated['effect'] === 'credit') ? $validated['adjustment_amount'] : 0;

                        CustomerLedger::create([
                            'customer_id' => $customer_account->customer_id,
                            'sale_id' => $customer_account->sale_id,
                            'transaction_id' => $transactionId,
                            'transaction_type' => $type,
                            'transaction_date' => now(),
                            'description' => $validated['notes'],
                            'debit' => $debit,
                            'credit' => $credit,
                            'received_by' => Auth::user()->name,
                        ]);

                        // Update the bill record itself
                        if ($debit > 0) {
                            $customer_account->increment('amount', $debit); // A debit increases the total bill amount
                        }
                        if ($credit > 0) {
                            $customer_account->increment('paid_amount', $credit); // A credit acts like a payment/discount
                        }
                        break;
                }

                // Finally, update the status of the bill
                if ($customer_account->balance <= 0.01) {
                    $customer_account->status = 'paid';
                    $customer_account->paid_date = now();

                    // THIS IS THE CRITICAL FIX: Update the related Sale status
                    if ($customer_account->sale) {
                        $customer_account->sale->update(['status' => 'paid']);
                    }

                } else {
                    $customer_account->status = 'partially_paid';

                    // Also handle the case where a paid bill becomes partially paid again (e.g., a new charge is added)
                    if ($customer_account->sale) {
                        $customer_account->sale->update(['status' => 'due']);
                    }
                }
                $customer_account->save();

            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Redirect back with validation errors
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Redirect back with a generic error
            return redirect()->back()->withErrors(['db_error' => 'An unexpected error occurred: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('customer_accounts.index')->with('success', 'Transaction processed successfully.');
    }

    public function showLedger(CustomerAccount $customer_account)
    {
        // Load the bill details and ONLY the ledger entries for THIS specific bill's sale_id
        $customer_account->load([
            'customer',
            'sale.ledgers' => function ($query) {
                $query->orderBy('transaction_date', 'asc')->orderBy('id', 'asc');
            }
        ]);

        return view('customer_accounts.show_ledger', compact('customer_account'));
    }
}
