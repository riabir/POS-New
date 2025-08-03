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
        $customers = Customer::has('ledgers')->orderBy('customer_name')->get();
        return view('customer_ledgers.index', compact('customers'));
    }

    /**
     * Display the detailed ledger for a single customer.
     */
    public function show(Customer $customer)
    {
        $customer->load(['ledgers' => function ($query) {
            $query->with('sale')
                  ->orderBy('transaction_date', 'asc')
                  ->orderBy('id', 'asc');
        }]);
        
        return view('customer_ledgers.show', compact('customer'));
    }
}