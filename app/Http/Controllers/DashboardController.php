<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\CustomerAccount;
use App\Models\VendorAccount;
use App\Models\Stock;
use App\Models\CustomerLedger;
use App\Models\VendorLedger;
use App\Models\Payout;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard with live, at-a-glance data.
     */
    public function index()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Calculations
        $todaysSales = Sale::whereDate('bill_date', $today)->sum('grand_total');
        $monthlySales = Sale::whereYear('bill_date', $currentYear)->whereMonth('bill_date', $currentMonth)->sum('grand_total');
        $yearlySales = Sale::whereYear('bill_date', $currentYear)->sum('grand_total');
        $customerUnpaidAmount = CustomerAccount::where('status', '!=', 'paid')->sum(DB::raw('amount - paid_amount'));
        $vendorUnpaidAmount = VendorAccount::where('status', '!=', 'paid')->sum(DB::raw('amount - paid_amount'));
        $companyAssetValue = Stock::sum(DB::raw('quantity * lsp'));
        $totalCashIn = CustomerLedger::where('transaction_type', 'new_payment')->sum('credit');
        $totalCashOutToVendors = VendorLedger::where('description', 'like', 'Payment for%')->sum('debit');
        $totalPayouts = Payout::sum('amount');
        $liquidCash = $totalCashIn - $totalCashOutToVendors - $totalPayouts;
        $recentSales = Sale::with('customer')->latest()->take(5)->get();

        // Pass all variables to the view
        return view('dashboard', compact(
            'todaysSales',
            'monthlySales',
            'yearlySales',
            'customerUnpaidAmount',
            'vendorUnpaidAmount',
            'companyAssetValue',
            'liquidCash',
            'recentSales'
        ));
    }
}