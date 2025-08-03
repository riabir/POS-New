<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource with filtering.
     */
     public function index(Request $request)
    {
        $query = ExpenseType::query();

        // --- FILTERING LOGIC (unchanged) ---
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // --- MODIFIED LINE ---
        // Change latest() to orderBy('name', 'asc') for alphabetical order
        $expense_types = $query->orderBy('name', 'asc')->paginate(10)->appends($request->query());

        return view('expense_types.index', compact('expense_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        // Validate that 'names' is an array and each item in it is a unique, required string.
        $validated = $request->validate([
            'names' => 'required|array|min:1',
            'names.*' => 'required|string|max:255|unique:expense_types,name',
        ]);

        // Use a database transaction to ensure all or none are created.
        DB::transaction(function () use ($validated) {
            foreach ($validated['names'] as $name) {
                // We only create if the name is not empty
                if (!empty($name)) {
                    ExpenseType::create(['name' => $name]);
                }
            }
        });

        return redirect()->route('expense_types.index')->with('success', 'Expense Type(s) created successfully.');
    }

    public function edit(ExpenseType $expense_type)
    {
        return view('expense_types.edit', compact('expense_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseType $expense_type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_types,name,' . $expense_type->id,
        ]);

        $expense_type->update($validated);

        return redirect()->route('expense_types.index')->with('success', 'Expense Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseType $expense_type)
    {
        $expense_type->delete();
        return redirect()->route('expense_types.index')->with('success', 'Expense Type has been deleted.');
    }
}