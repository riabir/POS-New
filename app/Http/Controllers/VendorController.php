<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:employees',         
            'address' => 'required|string',
            'concern_person' => 'required|string',
        ]);

        Vendor::create($validated);

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    // Edit vendor
    public function edit($id){
        $vendor= Vendor::find($id);
        //return $employee;
        return view('vendors.edit',compact('vendor'));
    }

    //update file   
 public function update($id, Request $request)
    {
        //return $request->all();
        $vendor= Vendor::find($id);
        $vendor->vendor_name=$request->vendor_name;
        $vendor->phone=$request->phone;
        $vendor->email=$request->email;
        $vendor->address=$request->address;
        $vendor->concern_person=$request->concern_person;
        $vendor->save();
        return redirect()->route('vendors.index')->with('success', 'Vendor update successfully.');
    }

    //Delete file
     public function destroy($id){
         $vendor= Vendor::find($id);
         $vendor->delete();
         return redirect()->route('vendors.index')->with('success', 'Vendor has been deleted.');
    }
}