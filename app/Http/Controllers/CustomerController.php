<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Customer;
use phpDocumentor\Reflection\Types\Nullable;

class CustomerController extends Controller
{
    // For index File
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    // For Path Insert From
    public function create()
    {
        return view('customers.create');
    }

    // For Inser Data
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:11',
            'email' => 'required|email|unique:employees',
            'address' => 'required|string',
            'concern' => 'required|string',
            'designation' => 'required|string',
            'opening_balance' => 'nullable|numeric', // or use 'decimal:0,2' if supported
            'notes' => 'nullable|string|max:255',

        ]);

        $validated['created_by'] = auth()->id();

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    // Edit File
    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('customers.edit', compact('customer'));
    }


    //update file   
    public function update($id, Request $request)
    {

        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->concern = $request->concern;
        $customer->designation = $request->designation;
        $customer->opening_balance = $request->opening_balance;
        $customer->notes = $request->notes;
        $customer->save();
        return redirect()->route('customers.index')->with('success', 'Customer Update Successfully.');
    }

    //Delete file
     public function destroy($id){
         $customer= Customer::find($id);
         $customer->delete();
         return redirect()->route('customers.index')->with('success', 'Customer has been deleted.');
    }
}
