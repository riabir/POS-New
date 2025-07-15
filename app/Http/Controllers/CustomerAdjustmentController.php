<?php
// app/Http/Controllers/CustomerAdjustmentController.php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerAdjustmentController extends Controller
{
    public function create(Customer $customer)
    {
        return view('customer_adjustments.create', compact('customer'));
    }

    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|in:vat_adjustment,tax_adjustment,other_adjustment',
            'entry_type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255',
        ]);

        $debit = ($validated['entry_type'] == 'debit') ? $validated['amount'] : 0;
        $credit = ($validated['entry_type'] == 'credit') ? $validated['amount'] : 0;

        CustomerLedger::create([
            'customer_id' => $customer->id,
            'transaction_id' => 'ADJ-' . strtoupper(Str::random(8)),
            'transaction_type' => $validated['transaction_type'],
            'transaction_date' => $validated['transaction_date'],
            'description' => $validated['description'],
            'debit' => $debit,
            'credit' => $credit,
            'received_by' => ($debit > 0) ? Auth::user()->name : null,
            'bill_by' => ($credit > 0) ? Auth::user()->name : null,
        ]);

        return redirect()->route('customer_ledgers.show', $customer->id)
                         ->with('success', 'Adjustment recorded successfully.');
    }
}