<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Eager load the relationships you need on the index page
    $employees = Employee::with('salaryStructures')->latest()->paginate(10); // Runs just 2 queries total
    return view('employees.index', compact('employees'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'personal_phone_number' => 'required|string|max:20',
            'status' => 'required|boolean',

            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nid_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'cv_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'passport_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'tin_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'certificate_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            
            'reporting_manager' => 'nullable|string|max:255',
            'branches' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'nid_number' => 'nullable|string|max:255|unique:employees,nid_number',
            'fathers_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'designation' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:255|unique:employees,passport_number',
            'mothers_name' => 'nullable|string|max:255',
            'date_of_join' => 'nullable|date',
            'department' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'tin_number' => 'nullable|string|max:255|unique:employees,tin_number',
            'emergency_contact' => 'nullable|string|max:255',
            'date_of_probation' => 'nullable|date',

            'marital_status' => 'nullable|string|max:50',
            'blood_group' => 'nullable|string|max:10',
            'religion' => 'nullable|string|max:50',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',

            'education_details' => 'nullable|array',
            'education_details.*.degree' => 'nullable|string|max:255',
            'education_details.*.institute' => 'nullable|string|max:255',
            'education_details.*.year' => 'nullable|string|max:4',
            'education_details.*.result' => 'nullable|string|max:10',
        ]);

        $data = $request->except(['_token', '_method']);

        // Handle file uploads by iterating over a defined list
        $filesToUpload = ['photo', 'cv_pdf', 'nid_attachment', 'passport_attachment', 'tin_attachment', 'certificate_attachment'];
        foreach ($filesToUpload as $file) {
            if ($request->hasFile($file)) {
                // Store the file and get the path
                $path = $request->file($file)->store('employees/' . $file . 's', 'public');
                $data[$file] = $path;
            }
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('employees')->ignore($employee->id)],
            'personal_phone_number' => 'required|string|max:20',
            'status' => 'required|boolean',

            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nid_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'cv_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'passport_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'tin_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'certificate_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            
            'reporting_manager' => 'nullable|string|max:255',
            'branches' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'nid_number' => ['nullable', 'string', 'max:255', Rule::unique('employees')->ignore($employee->id)],
            'fathers_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'designation' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'passport_number' => ['nullable', 'string', 'max:255', Rule::unique('employees')->ignore($employee->id)],
            'mothers_name' => 'nullable|string|max:255',
            'date_of_join' => 'nullable|date',
            'department' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'tin_number' => ['nullable', 'string', 'max:255', Rule::unique('employees')->ignore($employee->id)],
            'emergency_contact' => 'nullable|string|max:255',
            'date_of_probation' => 'nullable|date',

            'marital_status' => 'nullable|string|max:50',
            'blood_group' => 'nullable|string|max:10',
            'religion' => 'nullable|string|max:50',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',

            'education_details' => 'nullable|array',
            'education_details.*.degree' => 'nullable|string|max:255',
            'education_details.*.institute' => 'nullable|string|max:255',
            'education_details.*.year' => 'nullable|string|max:4',
            'education_details.*.result' => 'nullable|string|max:10',
        ]);

        $data = $request->except(['_token', '_method']);

        // Handle file uploads
        $filesToUpload = ['photo', 'cv_pdf', 'nid_attachment', 'passport_attachment', 'tin_attachment', 'certificate_attachment'];
        foreach ($filesToUpload as $file) {
            if ($request->hasFile($file)) {
                // Delete old file if it exists
                if ($employee->$file) {
                    Storage::disk('public')->delete($employee->$file);
                }
                $path = $request->file($file)->store('employees/' . $file . 's', 'public');
                $data[$file] = $path;
            }
        }
        
        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // Delete associated files from storage
        $filesToDelete = ['photo', 'cv_pdf', 'nid_attachment', 'passport_attachment', 'tin_attachment', 'certificate_attachment'];
        foreach($filesToDelete as $file){
            if($employee->$file){
                Storage::disk('public')->delete($employee->$file);
            }
        }

        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}