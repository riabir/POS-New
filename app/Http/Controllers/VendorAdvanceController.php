<?php
namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorAdvanceController extends Controller
{
    /**
     * Show the form for creating a new advance payment.
     */
    public function create()
    {
        $vendors = Vendor::orderBy('vendor_name')->get();
        return view('vendor_advances.create', compact('vendors'));
    }

    /**
     * Store a newly created advance payment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'payment_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_type' => ['required', 'string', 'in:Cash,Bank Transfer,Cheque'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request) {
            // Record the advance payment in the asset account
            VendorLedger::create([
                'vendor_id' => $request->vendor_id,
                'purchase_id' => null,
                'transaction_date' => $request->transaction_date,
                'description' => 'Advance Payment',
                'received_by' => Auth::user()->name,
                'payment_type' => $request->payment_type,
                'notes' => $request->notes,
                'debit' => $request->payment_amount,
                'credit' => 0,
                'account_type' => 'asset',
                'transaction_type' => 'advance',
            ]);
        });

        return redirect()->route('vendor_advances.create')
            ->with('success', 'Advance payment of ' . number_format($request->payment_amount, 2) . ' recorded successfully.');
    }
}