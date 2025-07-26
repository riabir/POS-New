<?php

namespace App\Http\Controllers;

use App\Models\SalaryStructure;
use App\Models\Employee;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load employee to avoid N+1 problem in the view
        $salaryStructures = SalaryStructure::with('employee')->latest('effective_date')->paginate(15);
        return view('salary_structures.index', compact('salaryStructures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get active employees for the dropdown
        $employees = Employee::where('status', true)->orderBy('first_name')->get();
        return view('salary_structures.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'house_rent_allowance' => 'required|numeric|min:0',
            'medical_allowance' => 'required|numeric|min:0',
            'conveyance_allowance' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        SalaryStructure::create($validatedData);

        return redirect()->route('salary_structures.index')->with('success', 'Salary structure created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryStructure $salaryStructure)
    {
        // Eager load the employee relationship for the detail view
        $salaryStructure->load('employee');
        return view('salary_structures.show', compact('salaryStructure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryStructure $salaryStructure)
    {
        $employees = Employee::where('status', true)->orderBy('first_name')->get();
        return view('salary_structures.edit', compact('salaryStructure', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryStructure $salaryStructure)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id', // Keep for validation, though not user-editable
            'basic_salary' => 'required|numeric|min:0',
            'house_rent_allowance' => 'required|numeric|min:0',
            'medical_allowance' => 'required|numeric|min:0',
            'conveyance_allowance' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $salaryStructure->update($validatedData);

        return redirect()->route('salary_structures.index')->with('success', 'Salary structure updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryStructure $salaryStructure)
    {
        $salaryStructure->delete();
        return redirect()->route('salary_structures.index')->with('success', 'Salary structure deleted successfully.');
    }
}