<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        // ... your existing index code ...
        $query = Vendor::query();
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        if ($request->filled('vendor_name')) {
            $query->where('vendor_name', 'like', '%' . $request->vendor_name . '%');
        }
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
        $vendors = $query->latest()->paginate(15)->appends($request->query());
        return view('vendors.index', compact('vendors'));
    }

     public function show(Vendor $vendor)
    {
        
        return view('vendors.show', compact('vendor'));
    }

    public function create()
    {
        // This method's only job is to show the form view.
        return view('vendors.create');
    }
    // ===================================

    public function store(Request $request)
    {
        // ... your existing store code ...
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:vendors', // FIX: Should check 'vendors' table, not 'employees'
            'address' => 'required|string',
            'concern_person' => 'required|string',
            'designation' => 'required|string',
        ]);
        Vendor::create($validated);
        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    // ... your other methods (edit, update, destroy) are fine.
    public function edit($id)
    {
        $vendor = Vendor::find($id);
        return view('vendors.edit', compact('vendor'));
    }

    public function update($id, Request $request)
    {
        $vendor = Vendor::find($id);
        $vendor->vendor_name = $request->vendor_name;
        $vendor->phone = $request->phone;
        $vendor->email = $request->email;
        $vendor->address = $request->address;
        $vendor->concern_person = $request->concern_person;
        $vendor->designation = $request->designation;
        $vendor->save();
        return redirect()->route('vendors.index')->with('success', 'Vendor update successfully.');
    }

    public function destroy($id)
    {
        $vendor = Vendor::find($id);
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor has been deleted.');
    }
}