<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Stock;
use App\Models\CustomerLedger;
use App\Models\VendorLedger;
use App\Models\Payout;
use App\Models\Expense;
use App\Models\ShareholderTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard with a clear and accurate financial snapshot.
     * This version uses caching for performance.
     */
    public function index()
    {
        // We wrap the data gathering in a cache block.
        // It will be automatically refreshed when any related transaction is made (if observers are set up).
        $dashboardData = Cache::remember('dashboard_financials', now()->addMinutes(60), function () {
            
            // --- Sales Metrics (for top cards) ---
            $today = Carbon::today();
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $todaysSales = Sale::whereDate('bill_date', $today)->sum('grand_total');
            $monthlySales = Sale::whereYear('bill_date', $currentYear)->whereMonth('bill_date', $currentMonth)->sum('grand_total');
            $yearlySales = Sale::whereYear('bill_date', $currentYear)->sum('grand_total');
            $recentSales = Sale::with('customer')->latest()->take(5)->get();

            // =================================================================
            //  NEW & ACCURATE FINANCIAL CALCULATIONS
            // =================================================================

            // --- A) LIQUID CASH (CASH FLOW) CALCULATION ---

            // --- All sources of CASH IN ---
            $customerPaymentsIn = CustomerLedger::whereIn('transaction_type', ['payment', 'new_payment', 'advance'])->sum('credit');
            $vendorRefundsIn = VendorLedger::where('transaction_type', 'refund_payable')->sum('debit');
            $shareholderInvestmentsIn = ShareholderTransaction::where('type', 'Investment')->sum('amount');
            
            $totalCashIn = $customerPaymentsIn + $vendorRefundsIn + $shareholderInvestmentsIn;

            // --- All sources of CASH OUT ---
            $vendorPaymentsOut = VendorLedger::whereIn('transaction_type', ['payment', 'advance'])->sum('debit');
            $customerRefundsOut = CustomerLedger::where('transaction_type', 'refund_payable')->sum('credit');
            $employeePayoutsOut = Payout::sum('amount');
            $paidExpensesOut = Expense::where('status', 'paid')->sum('total');
            $shareholderWithdrawalsOut = ShareholderTransaction::whereIn('type', ['Withdrawal', 'Salary'])->sum('amount');

            $totalCashOut = $vendorPaymentsOut + $customerRefundsOut + $employeePayoutsOut + $paidExpensesOut + $shareholderWithdrawalsOut;
            
            // Final Liquid Cash
            $liquidCash = $totalCashIn - $totalCashOut;


            // --- B) ASSETS & LIABILITIES (BALANCE SHEET) CALCULATION ---
            
            // --- Assets ---
            $inventoryValue = Stock::sum(DB::raw('quantity * lsp'));
            $vendorAdvancesPaid = VendorLedger::where('account_type', 'asset')->sum(DB::raw('debit - credit'));
            
            // Final Total Assets
            $totalAssets = $liquidCash + $inventoryValue + $vendorAdvancesPaid;

            // --- Liabilities ---
            $customerDues = Customer::get()->sum('payable_balance'); // <-- Calculation happens here
            $vendorDues = Vendor::get()->sum('payable_balance');
            $customerAdvancesHeld = CustomerLedger::where('account_type', 'asset')->sum(DB::raw('credit - debit'));
            
            // Final Total Liabilities
            $totalLiabilities = $vendorDues + $customerAdvancesHeld;


            // Return all the data as a single array to be cached.
            // This array is what gets stored in the cache and returned by the closure.
            return [
                'todaysSales' => $todaysSales,
                'monthlySales' => $monthlySales,
                'yearlySales' => $yearlySales,
                'recentSales' => $recentSales,
                'totalAssets' => $totalAssets,
                'totalLiabilities' => $totalLiabilities,
                'liquidCash' => $liquidCash,
                'inventoryValue' => $inventoryValue,
                'customerDues' => $customerDues, // <-- The variable is added to the array
                'vendorDues' => $vendorDues,
                'customerAdvancesHeld' => $customerAdvancesHeld,
                'vendorAdvancesPaid' => $vendorAdvancesPaid
            ];
        });

        // =================================================================
        //  FIXED: Pass the entire $dashboardData array to the view.
        // =================================================================
        // The view will automatically "extract" the keys from this array
        // into variables with the same name (e.g., $customerDues, $totalAssets, etc.).
        return view('dashboard', $dashboardData);
    }
}