<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers with filtering.
     */
    public function index(Request $request)
    {
        $query = Customer::latest();

        // Filter by Customer ID
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        // Filter by Customer Name
        if ($request->filled('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }

        // Filter by Phone Number
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        $customers = $query->paginate(15)->appends($request->query());

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        // FIX: 'customer_name' typo corrected. Unique check should be on 'customers' table.
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'email' => 'required|email|unique:customers,email',
            'address' => 'required|string',
            'concern' => 'required|string',
            'designation' => 'required|string',
            'opening_balance' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', Rule::unique('customers')->ignore($customer->id)],
            'email' => ['required', 'email', Rule::unique('customers')->ignore($customer->id)],
            'address' => 'required|string',
            'concern' => 'required|string',
            'designation' => 'required|string',
            'opening_balance' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
         $customer->delete();
         return redirect()->route('customers.index')->with('success', 'Customer has been deleted.');
    }
}