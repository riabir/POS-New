<?php
// app/Http/Controllers/CustomerRefundController.php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerRefundController extends Controller
{
    /**
     * Display a listing of all refunds.
     */
    public function index()
    {
        $refunds = CustomerLedger::where('transaction_type', 'refund')
            ->with('customer')
            ->latest('transaction_date')
            ->paginate(15);
            
        return view('customer_refunds.index', compact('refunds'));
    }

    /**
     * Show the form for creating a new refund.
     */
    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('customer_refunds.create', compact('customers'));
    }

    /**
     * Show the form for creating a new refund for a specific customer.
     */
    public function createForCustomer(Customer $customer)
    {
        return view('customer_refunds.create_for_customer', compact('customer'));
    }

    /**
     * Store a newly created refund in storage.
     */
    public function store(Request $request, Customer $customer)
    {
        $request->validate([
            'refund_amount' => ['required', 'numeric', 'min:0.01'],
            'refund_type' => ['required', 'in:advance,payable'],
            'payment_type' => ['required', 'string', 'in:Cash,Bank Transfer,Cheque'],
            'refund_date' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        // Validate that refund amount doesn't exceed available balance
        if ($request->refund_type === 'advance' && $request->refund_amount > $customer->available_advance) {
            return redirect()->back()
                ->withErrors(['refund_amount' => 'Refund amount cannot exceed available advance balance'])
                ->withInput();
        }

        if ($request->refund_type === 'payable' && $request->refund_amount > $customer->payable_balance) {
            return redirect()->back()
                ->withErrors(['refund_amount' => 'Refund amount cannot exceed payable balance'])
                ->withInput();
        }

        DB::transaction(function () use ($request, $customer) {
            $transactionId = 'REF-' . strtoupper(Str::random(8));
            
            if ($request->refund_type === 'advance') {
                // Reduce customer's advance (asset account)
                CustomerLedger::create([
                    'customer_id' => $customer->id,
                    'transaction_id' => $transactionId,
                    'transaction_type' => 'refund',
                    'transaction_date' => $request->refund_date,
                    'description' => 'Refund: ' . $request->reason,
                    'debit' => $request->refund_amount, // Debit reduces asset
                    'credit' => 0,
                    'payment_type' => $request->payment_type,
                    'bill_by' => Auth::user()->name,
                    'account_type' => 'asset',
                ]);
            } else {
                // Reduce customer's payable balance (liability account)
                CustomerLedger::create([
                    'customer_id' => $customer->id,
                    'transaction_id' => $transactionId,
                    'transaction_type' => 'refund',
                    'transaction_date' => $request->refund_date,
                    'description' => 'Refund: ' . $request->reason,
                    'debit' => 0,
                    'credit' => $request->refund_amount, // Credit reduces liability
                    'payment_type' => $request->payment_type,
                    'bill_by' => Auth::user()->name,
                    'account_type' => 'liability',
                ]);
            }
        });

        return redirect()->route('customer_refunds.index')
            ->with('success', 'Refund of ' . number_format($request->refund_amount, 2) . ' processed successfully.');
    }
}