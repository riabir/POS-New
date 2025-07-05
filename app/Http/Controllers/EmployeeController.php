<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }


// Edit File
    public function edit($id){
        $employee= Employee::find($id);
        //return $employee;
        return view('employees.edit',compact('employee'));
    }


 //update file   
 public function update($id, Request $request)
    {
        //return $request->all();
        $employee= Employee::find($id);
        $employee->first_name=$request->first_name;
        $employee->last_name=$request->last_name;
        $employee->email=$request->email;
        $employee->phone=$request->phone;
        $employee->address=$request->address;
        $employee->save();
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    //Delete file
     public function destroy($id){
         $employee= Employee::find($id);
         $employee->delete();
         return redirect()->route('employees.index')->with('success', 'Employee has been deleted.');
    }
}
