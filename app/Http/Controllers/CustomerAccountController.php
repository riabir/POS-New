<?php
namespace App\Http\Controllers;
use App\Models\CustomerAccount;
use App\Models\CustomerLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class CustomerAccountController extends Controller
{
    /**
     * Display a listing of unpaid bills.
     */
    public function index()
    {
        $unpaidBills = CustomerAccount::with(['sale', 'customer'])
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->latest('id')
            ->paginate(10);
        return view('customer_accounts.index', compact('unpaidBills'));
    }
    
    /**
     * Process payment for a customer account.
     */
    public function processPayment(Request $request, CustomerAccount $customer_account)
    {
        $customer = $customer_account->customer;
        $currentBalance = $customer_account->balance;
        $availableAdvance = $customer->available_advance;
        // Calculate the maximum advance that can be applied
        $maxAdvanceForThisBill = min($currentBalance, $availableAdvance);
        
        $validated = $request->validate([
            'transaction_type' => ['required', Rule::in(['payment', 'advance_adjustment', 'vat_adjustment', 'tax_adjustment', 'other_adjustment'])],
        ]);
        
        $type = $validated['transaction_type'];
        
        // Dynamic validation based on transaction type
        if ($type === 'payment') {
            $request->validate([
                'payment_amount' => ['required', 'numeric', 'min:0.01'],
                'payment_type' => ['required', 'string', 'in:Cash,Bank Transfer,Cheque'],
            ]);
        } elseif ($type === 'advance_adjustment') {
            $request->validate([
                'advance_amount' => ['required', 'numeric', 'min:0.01', 'max:' . $maxAdvanceForThisBill],
            ]);
        } else {
            $request->validate([
                'adjustment_amount' => ['required', 'numeric', 'min:0.01'],
                'effect' => ['required', 'in:debit,credit'],
                'notes' => ['required', 'string', 'max:255'],
            ]);
        }
        
        try {
            DB::transaction(function () use ($request, $type, $customer_account, $customer) {
                $transactionId = strtoupper(substr($type, 0, 3)) . '-' . strtoupper(Str::random(8));
                
                switch ($type) {
                    case 'payment':
                        CustomerLedger::create([
                            'customer_id' => $customer_account->customer_id,
                            'sale_id' => $customer_account->sale_id,
                            'transaction_id' => $transactionId,
                            'transaction_type' => $type,
                            'transaction_date' => now(),
                            'description' => 'Payment for Invoice: ' . $customer_account->sale->bill_no,
                            'debit' => 0,
                            'credit' => $request->payment_amount, // Credit for payments
                            'payment_type' => $request->payment_type,
                            'received_by' => Auth::user()->name,
                            'account_type' => 'liability',
                        ]);
                        $customer_account->increment('paid_amount', $request->payment_amount);
                        break;
                        
                    case 'advance_adjustment':
                        // Record in asset account (reducing advance)
                        CustomerLedger::create([
                            'customer_id' => $customer_account->customer_id,
                            'sale_id' => $customer_account->sale_id,
                            'transaction_id' => $transactionId,
                            'transaction_type' => $type,
                            'transaction_date' => now(),
                            'description' => 'Advance Applied to Invoice: ' . $customer_account->sale->bill_no,
                            'debit' => $request->advance_amount, // Debit for advance usage
                            'credit' => 0,
                            'received_by' => Auth::user()->name,
                            'account_type' => 'asset',
                        ]);
                        // Record in liability account (reducing payable)
                        CustomerLedger::create([
                            'customer_id' => $customer_account->customer_id,
                            'sale_id' => $customer_account->sale_id,
                            'transaction_id' => $transactionId,
                            'transaction_type' => $type,
                            'transaction_date' => now(),
                            'description' => 'Advance Applied to Invoice: ' . $customer_account->sale->bill_no,
                            'debit' => 0,
                            'credit' => $request->advance_amount, // Credit for advance application
                            'received_by' => Auth::user()->name,
                            'account_type' => 'liability',
                        ]);
                        $customer_account->increment('paid_amount', $request->advance_amount);
                        break;
                        
                    case 'vat_adjustment':
                    case 'tax_adjustment':
                    case 'other_adjustment':
                        $debit = ($request->effect === 'debit') ? $request->adjustment_amount : 0;
                        $credit = ($request->effect === 'credit') ? $request->adjustment_amount : 0;
                        
                        CustomerLedger::create([
                            'customer_id' => $customer_account->customer_id,
                            'sale_id' => $customer_account->sale_id,
                            'transaction_id' => $transactionId,
                            'transaction_type' => $type,
                            'transaction_date' => now(),
                            'description' => $request->notes,
                            'debit' => $debit,
                            'credit' => $credit,
                            'received_by' => ($debit > 0) ? Auth::user()->name : null,
                            'bill_by' => ($credit > 0) ? Auth::user()->name : null,
                            'account_type' => 'liability',
                        ]);
                        
                        // Do NOT modify the original bill amount for adjustments
                        // The balance will be calculated based on ledger entries
                        break;
                }
                
                // Update the status of the bill based on the calculated balance
                if ($customer_account->balance <= 0.01) {
                    $customer_account->status = 'paid';
                    $customer_account->paid_date = now();
                    if ($customer_account->sale) {
                        $customer_account->sale->update(['status' => 'paid']);
                    }
                } else {
                    $customer_account->status = 'partially_paid';
                    if ($customer_account->sale) {
                        $customer_account->sale->update(['status' => 'due']);
                    }
                }
                $customer_account->save();
            });
            
            return redirect()->route('customer_accounts.index')->with('success', 'Transaction processed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['db_error' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Show the ledger for a specific customer account.
     */
    public function showLedger(CustomerAccount $customer_account)
    {
        $customer_account->load([
            'customer',
            'sale.ledgers' => function ($query) {
                $query->orderBy('transaction_date', 'asc')->orderBy('id', 'asc');
            }
        ]);
        return view('customer_accounts.show_ledger', compact('customer_account'));
    }
}