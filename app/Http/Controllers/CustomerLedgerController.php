<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerLedgerController extends Controller
{
    /**
     * Display a list of customers who have ledger entries.
     */
    public function index()
    {
        // 1. Fetch all customers from the database who have at least one ledger entry.
        // 2. Order them by name for easy navigation.
        $customers = Customer::has('ledgers')->orderBy('customer_name')->get();

        // 3. Load the 'customer_ledgers.index' view and pass the fetched customers
        //    to it inside a variable named 'customers'.
        return view('customer_ledgers.index', compact('customers'));
    }

   /**
    * Display the detailed ledger for a single customer.
    */
   public function show(Customer $customer)
    {
        // Eager load the 'ledgers' relationship along with the sale for each ledger entry.
        // Order by date and then ID to ensure transactions on the same day are in the correct sequence.
        $customer->load(['ledgers' => function ($query) {
            $query->with('sale')
                  ->orderBy('transaction_date', 'asc')
                  ->orderBy('id', 'asc');
        }]);

        // Note: The view file for this is 'customer_ledgers.show', NOT 'show_ledger'
        return view('customer_ledgers.show', compact('customer'));
    }
}