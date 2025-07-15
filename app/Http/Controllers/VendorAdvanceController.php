<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorAdvanceController extends Controller
{
    public function create()
    {
        $vendors = Vendor::orderBy('vendor_name')->get();
        return view('vendor_advances.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'payment_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_type' => ['required', 'string', 'in:Cash,Bank Transfer,Cheque'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

         DB::transaction(function () use ($validated) {
            // Create the DEBIT entry for the advance payment
            VendorLedger::create([
                'vendor_id' => $validated['vendor_id'],
                'purchase_id' => null, // No purchase is attached to an advance
                'transaction_date' => $validated['transaction_date'],
                'description' => 'Advance Payment',
                'received_by' => Auth::user()->name,
                'payment_type' => $validated['payment_type'],
                'notes' => $validated['notes'],
                'debit' => $validated['payment_amount'],
                'credit' => 0,
            ]);
        });

        return redirect()->route('vendor_advances.create')
            ->with('success', 'Advance payment of ' . number_format($request->payment_amount, 2) . ' recorded successfully.');
    }
}