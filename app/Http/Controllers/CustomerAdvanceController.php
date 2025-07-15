<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerAdvanceController extends Controller
{
    /**
     * Show the form for creating a new advance payment.
     */
    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('customer_advances.create', compact('customers'));
    }

    /**
     * Store a newly created advance payment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'payment_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_type' => ['required', 'string', 'in:Cash,Bank Transfer,Cheque'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request) {
            CustomerLedger::create([
                'customer_id' => $request->customer_id,
                'transaction_id' => 'ADV-' . strtoupper(Str::random(8)), // New
                'transaction_type' => 'advance_payment', // New
                'transaction_date' => $request->transaction_date,
                'description' => 'Advance Payment Received',
                'credit' => $request->payment_amount, // A payment is a DEBIT
                'debit' => 0,
                'payment_type' => $request->payment_type,
                'received_by' => Auth::user()->name,
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('customer_advances.create')
            ->with('success', 'Advance payment of ' . number_format($request->payment_amount, 2) . ' recorded successfully.');
    }
}