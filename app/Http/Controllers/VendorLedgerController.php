<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorLedgerController extends Controller
{
    public function index()
    {
        $vendors = Vendor::has('ledgers')->orderBy('vendor_name')->get();
        return view('vendor_ledgers.index', compact('vendors'));
    }

   public function show(Vendor $vendor)
    {
        // Eager load the 'ledgers' relationship along with the purchase for each ledger entry.
        // Order by date and then ID to ensure transactions on the same day are in the correct order.
        $vendor->load(['ledgers' => function ($query) {
            $query->with('purchase')
                  ->orderBy('transaction_date', 'asc')
                  ->orderBy('id', 'asc');
        }]);

        return view('vendor_ledgers.show', compact('vendor'));
    }
}