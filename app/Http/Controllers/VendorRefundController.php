<?php
namespace App\Http\Controllers;
use App\Models\Vendor;
use App\Models\VendorLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VendorRefundController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();
        if ($request->filled('search_vendor')) {
            $searchTerm = $request->search_vendor;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('vendor_name', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }
        $vendors = $query->with('ledgers')->latest()->paginate(15)->withQueryString();
        return view('vendor_refunds.index', compact('vendors'));
    }

    public function create(Vendor $vendor)
    {
        return view('vendor_refunds.create', compact('vendor'));
    }

    public function store(Request $request, Vendor $vendor)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01',
            'refund_type'   => 'required|string|in:payable,advance',
            'payment_type'  => 'required|string|max:50',
            'refund_date'   => 'required|date',
            'reason'        => 'required|string|max:1000',
        ]);

        $refundAmount = $request->refund_amount;

        DB::transaction(function () use ($request, $vendor, $refundAmount) {
            // Validate against the correct balance
            if ($request->refund_type === 'payable' && $refundAmount > $vendor->payable_balance) {
                throw ValidationException::withMessages([
                    'refund_amount' => 'Refund amount cannot exceed the payable balance of ' . number_format($vendor->payable_balance, 2),
                ]);
            } elseif ($request->refund_type === 'advance' && $refundAmount > $vendor->available_advance) {
                throw ValidationException::withMessages([
                    'refund_amount' => 'Refund amount cannot exceed the available advance of ' . number_format($vendor->available_advance, 2),
                ]);
            }

            // Determine account type and transaction type
            if ($request->refund_type === 'payable') {
                // Refund from payable: We are giving money back to the vendor for a payable (liability)
                // This reduces our liability, so we debit the liability account
                $accountType = 'liability';
                $transactionType = 'refund_payable';
                $debit = $refundAmount;
                $credit = 0;
            } else {
                // Refund from advance: We are giving back an advance payment (asset)
                // This reduces our asset, so we credit the asset account
                $accountType = 'asset';
                $transactionType = 'refund_advance';
                $debit = 0;
                $credit = $refundAmount;
            }

            VendorLedger::create([
                'vendor_id'         => $vendor->id,
                'transaction_date'  => $request->refund_date,
                'description'       => 'Vendor Refund. Reason: ' . $request->reason,
                'transaction_type'  => $transactionType,
                'payment_type'      => $request->payment_type,
                'debit'             => $debit,
                'credit'            => $credit,
                'bill_by'           => Auth::user()->name,
                'account_type'      => $accountType,
            ]);
        });

        return redirect()->route('vendor_refunds.index')
                         ->with('success', 'Refund of ' . number_format($refundAmount, 2) . ' processed successfully for ' . $vendor->vendor_name . '.');
    }
}