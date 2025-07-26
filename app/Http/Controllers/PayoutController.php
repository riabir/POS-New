<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payouts = Payout::with('employee')->latest('payout_date')->paginate(15);
        return view('payouts.index', compact('payouts'));
    }

    /**
     * Show the form for creating a new individual resource.
     */
    public function create()
    {
        $employees = Employee::where('status', true)->orderBy('first_name')->get();
        $payoutTypes = Payout::getPayoutTypes();
        return view('payouts.create', compact('employees', 'payoutTypes'));
    }

    /**
     * Store a newly created individual resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payout_type' => 'required|string|max:255',
            'payout_date' => 'required|date',
            'notes' => 'nullable|string',
            'calculation_mode' => 'required|in:fixed,percentage',
            'amount' => 'required_if:calculation_mode,fixed|nullable|numeric|min:0.01',
            'percentage' => 'required_if:calculation_mode,percentage|nullable|numeric|min:1|max:200',
        ]);

        $data = $request->only('employee_id', 'payout_type', 'payout_date', 'notes');

        if ($request->calculation_mode === 'percentage') {
            $employee = Employee::findOrFail($request->employee_id);
            $currentSalary = $employee->currentSalaryStructure();

            if (!$currentSalary || $currentSalary->basic_salary <= 0) {
                return back()->withInput()->withErrors(['percentage' => 'This employee does not have a valid salary structure to calculate a percentage from.']);
            }

            $basic_salary = $currentSalary->basic_salary;
            $data['amount'] = ($basic_salary * $request->percentage) / 100;
            $data['notes'] = trim(($data['notes'] ?? '') . "\n[Calculation: {$request->percentage}% of basic salary $$basic_salary]");
        } else {
            $data['amount'] = $request->amount;
        }

        Payout::create($data);

        return redirect()->route('payouts.index')->with('success', 'Payout created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payout $payout)
    {
        $payout->load('employee');
        return view('payouts.show', compact('payout'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payout $payout)
    {
        // Note: Editing is disabled for percentage-based calculation for simplicity.
        // You would need more complex logic to reverse-calculate the percentage if desired.
        $employees = Employee::where('status', true)->orderBy('first_name')->get();
        $payoutTypes = Payout::getPayoutTypes();
        return view('payouts.edit', compact('payout', 'employees', 'payoutTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payout $payout)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payout_type' => 'required|string|max:255',
            'payout_date' => 'required|date',
            'notes' => 'nullable|string',
            // In edit, we only allow updating a fixed amount
            'amount' => 'required|numeric|min:0.01',
        ]);

        $data = $request->only('employee_id', 'payout_type', 'payout_date', 'notes', 'amount');
        $payout->update($data);

        return redirect()->route('payouts.index')->with('success', 'Payout updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payout $payout)
    {
        $payout->delete();
        return redirect()->route('payouts.index')->with('success', 'Payout deleted successfully.');
    }

    // --- BULK METHODS ---

    /**
     * Show the form for creating bulk payouts.
     */
    public function createBulk()
    {
        $payoutTypes = Payout::getPayoutTypes();
        return view('payouts.create_bulk', compact('payoutTypes'));
    }

    /**
     * Store the newly created bulk payouts in storage.
     */
    public function storeBulk(Request $request)
    {
        $validatedData = $request->validate([
            'payout_type' => 'required|string|max:255',
            'payout_date' => 'required|date',
            'notes' => 'nullable|string',
            'percentage' => 'required|numeric|min:1|max:200',
        ]);

        $employees = Employee::active()->whereHas('salaryStructures')->get();
        $payoutsToInsert = [];
        $skippedCount = 0;
        $now = Carbon::now();

        foreach ($employees as $employee) {
            $currentSalary = $employee->currentSalaryStructure();
            if ($currentSalary && $currentSalary->basic_salary > 0) {
                $basic_salary = $currentSalary->basic_salary;
                $amount = ($basic_salary * $request->percentage) / 100;
                $note = trim(($request->notes ?? '') . "\n[Calculation: {$request->percentage}% of basic salary $$basic_salary]");

                $payoutsToInsert[] = [
                    'employee_id' => $employee->id,
                    'payout_type' => $request->payout_type,
                    'payout_date' => $request->payout_date,
                    'amount' => $amount,
                    'notes' => $note,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            } else {
                $skippedCount++;
            }
        }

        if (!empty($payoutsToInsert)) {
            Payout::insert($payoutsToInsert);
        }

        $successMessage = count($payoutsToInsert) . ' payouts created successfully.';
        if ($skippedCount > 0) {
            $successMessage .= " ($skippedCount employees were skipped due to missing or invalid salary data.)";
        }

        return redirect()->route('payouts.index')->with('success', $successMessage);
    }
}