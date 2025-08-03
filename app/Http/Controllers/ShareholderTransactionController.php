<?php

namespace App\Http\Controllers;

use App\Models\Shareholder;
use App\Models\ShareholderTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShareholderTransactionController extends Controller
{
    /**
     * Show the form for creating a new transaction for a specific shareholder.
     *
     * @param  \App\Models\Shareholder  $shareholder
     * @return \Illuminate\View\View
     */
    public function create(Shareholder $shareholder)
    {
        // Get the allowed transaction types from the model constant
        $transactionTypes = ShareholderTransaction::ALL_TYPES;

        return view('shareholder_transactions.create', compact('shareholder', 'transactionTypes'));
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shareholder  $shareholder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Shareholder $shareholder)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            // Use the model constant for the validation rule
            'type' => ['required', Rule::in(ShareholderTransaction::ALL_TYPES)],
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
        ]);

        $shareholder->transactions()->create($validated);

        return redirect()->route('shareholders.show', $shareholder)
                         ->with('success', 'Transaction added successfully.');
    }

    /**
     * Show the form for editing the specified transaction.
     *
     * @param  \App\Models\Shareholder  $shareholder
     * @param  \App\Models\ShareholderTransaction  $transaction
     * @return \Illuminate\View\View
     */
    public function edit(Shareholder $shareholder, ShareholderTransaction $transaction)
    {
        // Best Practice: You could add authorization here to ensure the user can edit.
        // For example: $this->authorize('update', $transaction);

        $transactionTypes = ShareholderTransaction::ALL_TYPES;
        return view('shareholder_transactions.edit', compact('shareholder', 'transaction', 'transactionTypes'));
    }

    /**
     * Update the specified transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shareholder  $shareholder
     * @param  \App\Models\ShareholderTransaction  $transaction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Shareholder $shareholder, ShareholderTransaction $transaction)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'type' => ['required', Rule::in(ShareholderTransaction::ALL_TYPES)],
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
        ]);

        $transaction->update($validated);

        return redirect()->route('shareholders.show', $shareholder)
                         ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified transaction from storage.
     *
     * @param  \App\Models\Shareholder  $shareholder
     * @param  \App\Models\ShareholderTransaction  $transaction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Shareholder $shareholder, ShareholderTransaction $transaction)
    {
        // Best Practice: You could add authorization here.
        // For example: $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('shareholders.show', $shareholder)
                         ->with('success', 'Transaction deleted successfully.');
    }
}