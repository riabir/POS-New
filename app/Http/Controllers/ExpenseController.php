<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::all();
        return view('expenses.index', compact('expenses'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'expense_by' => 'required|string|max:255',
            'expense_type' => 'required|string|max:255',
            'remarks' => 'required|string|max:255',
            'amount' => 'required|numeric', // or use 'decimal:0,2' if supported
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    // Edit vendor
    public function edit($id)
    {
        $expense = Expense::find($id);
        //return $employee;
        return view('expenses.edit', compact('expense'));
    }


    //update file   
 public function update($id, Request $request)
    {
        //return $request->all();
        $expense= Expense::find($id);
        $expense->date=$request->date;
        $expense->expense_by=$request->expense_by;
        $expense->expense_type=$request->expense_type;
        $expense->remarks=$request->remarks;
        $expense->amount=$request->amount;
        $expense->save();
        return redirect()->route('expenses.index')->with('success', 'Expense update successfully.');
    }


    //Delete file
    public function destroy($id)
    {
        $expense = Expense::find($id);
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense has been deleted.');
    }

}
