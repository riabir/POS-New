<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseItem;
use App\Models\SaleItem;

class SerialSearchController extends Controller
{
    /**
     * Display a search form and show the lifecycle of a product by its serial number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get the serial number from the form submission, and trim any whitespace.
        $serial = $request->input('serial');

        $foundPurchases = collect();
        $foundSales = collect();

        // Only perform the search if a serial number was provided.
        if ($serial) {
            // --- Search for the Purchase Record ---
            // Use whereJsonContains to search within the JSON 'serial_numbers' array.
            // Eager load the parent 'purchase' and the related 'vendor' for efficiency.
            $purchaseItems = PurchaseItem::whereJsonContains('serial_numbers', $serial)
                                         ->with('purchase.vendor')
                                         ->get();
            
            // From the found items, get the unique parent Purchase models.
            // unique() prevents duplicates if, for some reason, the serial was in multiple items of the same purchase.
            $foundPurchases = $purchaseItems->pluck('purchase')->unique('id');


            // --- Search for the Sale Record ---
            // Similarly, search in SaleItem and eager load relations.
            $saleItems = SaleItem::whereJsonContains('serial_numbers', $serial)
                               ->with('sale.customer')
                               ->get();

            // From the found items, get the unique parent Sale models.
            $foundSales = $saleItems->pluck('sale')->unique('id');
        }

        // Return the view and pass the search term and the results.
        return view('search.serial_index', [
            'serial'         => $serial,
            'purchases'      => $foundPurchases,
            'sales'          => $foundSales,
        ]);
    }
}