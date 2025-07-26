<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function summaryReport(Request $request)
    {
        $reportTitle = '';
        $salesQuery = Sale::query();
        $purchaseQuery = Purchase::query();

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $dateFrom = Carbon::parse($request->date_from);
            $dateTo = Carbon::parse($request->date_to);
            $reportTitle = "Report for: " . $dateFrom->format('M d, Y') . " to " . $dateTo->format('M d, Y');
            $salesQuery->whereBetween('bill_date', [$dateFrom, $dateTo]);
            $purchaseQuery->whereBetween('purchase_date', [$dateFrom, $dateTo]);
        } else { // Fallback to month and year
            $selectedYear = $request->input('year', Carbon::now()->year);
            $selectedMonth = $request->input('month'); // Get month, could be empty

            if ($selectedMonth) { // If a month is selected
                $selectedMonth = (int) $selectedMonth;
                $reportTitle = "Report for: " . Carbon::create()->month($selectedMonth)->format('F') . " " . $selectedYear;
                $salesQuery->whereYear('bill_date', $selectedYear)->whereMonth('bill_date', $selectedMonth);
                $purchaseQuery->whereYear('purchase_date', $selectedYear)->whereMonth('purchase_date', $selectedMonth);
            } else { // If no month is selected, do a yearly report
                $reportTitle = "Report for Year: " . $selectedYear;
                $salesQuery->whereYear('bill_date', $selectedYear);
                $purchaseQuery->whereYear('purchase_date', $selectedYear);
            }
        }

        // ... the rest of the method is fine ...
        $totalSales = $salesQuery->sum('grand_total');
        $totalPurchases = $purchaseQuery->sum('grand_total');
        $salesForProfit = $salesQuery->with('items', 'commissions')->get();
        $totalProfit = $salesForProfit->sum('total_profit');

        $salesYears = Sale::selectRaw('YEAR(bill_date) as year')->distinct()->pluck('year');
        $purchaseYears = Purchase::selectRaw('YEAR(purchase_date) as year')->distinct()->pluck('year');
        $availableYears = $salesYears->merge($purchaseYears)->unique()->sortDesc();

        return view('reports.summary', compact(
            'reportTitle',
            'availableYears',
            'totalSales',
            'totalPurchases',
            'totalProfit'
        ));
    }
}
