<?php

namespace App\Http\Controllers;

use App\Models\Shareholder;
use App\Models\ShareholderTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;

class ShareholderController extends Controller
{
    /**
     * Display a listing of the shareholders.
     * This method is optimized for performance.
     */
    public function index(Request $request)
    {
        $query = Shareholder::query()
            ->withSum(
                ['transactions as credit_sum' => fn($q) => $q->whereIn('type', ShareholderTransaction::CREDIT_TYPES)],
                'amount'
            )
            ->withSum(
                ['transactions as debit_sum' => fn($q) => $q->whereNotIn('type', ShareholderTransaction::CREDIT_TYPES)],
                'amount'
            )
            ->latest();

        // --- FILTERING LOGIC ---

        // Filter by Shareholder ID
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        // Filter by Shareholder Name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by Phone Number
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        // --- END OF FILTERING LOGIC ---

        // Paginate the results and append the filter criteria to the pagination links
        $shareholders = $query->paginate(15)->appends($request->query());

        return view('shareholders.index', compact('shareholders'));
    }
    public function create()
    {
        return view('shareholders.create');
    }

    /**
     * Store a newly created shareholder and their initial investment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:shareholders,email',
            'phone' => 'nullable|string|max:20|unique:shareholders,phone',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'initial_investment' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // 1. Create the Shareholder master record.
                $shareholder = Shareholder::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'join_date' => $validated['join_date'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                // 2. Create the first transaction for the initial investment.
                $shareholder->transactions()->create([
                    'transaction_date' => $validated['join_date'],
                    'type' => 'Investment',
                    'amount' => $validated['initial_investment'],
                    'description' => 'Initial Investment',
                ]);
            });
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create shareholder. Error: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('shareholders.index')->with('success', 'Shareholder created successfully.');
    }


    /**
     * Display the specified shareholder's ledger with running balances in chronological order.
     * This is the updated method for chronological view.
     */
    public function show(Shareholder $shareholder)
    {
        // 1. Get transactions in CHRONOLOGICAL (oldest first) order.
        // We use reorder() to override the default DESC order from the model relationship.
        // We also add a secondary sort by ID as a tie-breaker for transactions on the same day.
        $transactions = $shareholder->transactions()
            ->reorder('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // 2. Start the running balance at 0 to calculate forwards.
        $runningBalance = 0;

        // 3. Use map() to create a new collection where each transaction has a running_balance property.
        // This logic is more intuitive as it mirrors how you'd calculate a balance on paper.
        $transactionsWithBalance = $transactions->map(function ($transaction) use (&$runningBalance) {
            // First, adjust the balance based on the current transaction's type.
            if ($transaction->is_credit) {
                $runningBalance += $transaction->amount;
            } else { // is_debit
                $runningBalance -= $transaction->amount;
            }

            // Then, assign the newly calculated balance to the transaction object.
            $transaction->running_balance = $runningBalance;

            return $transaction;
        });

        // 4. Pass the correctly ordered collection to the view.
        return view('shareholders.show', [
            'shareholder' => $shareholder,
            'transactions' => $transactionsWithBalance
        ]);
    }

    /**
     * Show the form for editing the specified shareholder's details.
     */
    public function edit(Shareholder $shareholder)
    {
        return view('shareholders.edit', compact('shareholder'));
    }

    /**
     * Update the specified shareholder's details in storage.
     */
    public function update(Request $request, Shareholder $shareholder)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('shareholders')->ignore($shareholder->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('shareholders')->ignore($shareholder->id)],
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'is_active' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        $shareholder->update($validated);

        return redirect()->route('shareholders.index')->with('success', 'Shareholder updated successfully.');
    }

    /**
     * Remove the specified shareholder and their transactions from storage.
     */
    public function destroy(Shareholder $shareholder)
    {
        // The onDelete('cascade') in your migration handles transaction deletion.
        $shareholder->delete();

        return redirect()->route('shareholders.index')->with('success', 'Shareholder and all related transactions deleted successfully.');
    }
}
