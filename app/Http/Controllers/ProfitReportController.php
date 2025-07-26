<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class ProfitReportController extends Controller
{
    /**
     * Display a paginated list of sales with their total profit.
     */
    public function index(Request $request)
    {
        $query = Sale::with('customer')->whereHas('items');

        // --- FILTERING LOGIC ---

        // Filter by Date Range: From
        if ($request->filled('date_from')) {
            $query->whereDate('bill_date', '>=', $request->date_from);
        }

        // Filter by Date Range: To
        if ($request->filled('date_to')) {
            $query->whereDate('bill_date', '<=', $request->date_to);
        }

        // Filter by Bill Number
        if ($request->filled('bill_no')) {
            $query->where('bill_no', 'like', '%' . $request->bill_no . '%');
        }

        // Filter by Customer ID (a direct 'where' on the sales table)
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by Customer Name (searches the related 'customers' table)
        if ($request->filled('customer_name')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->customer_name . '%');
            });
        }

        // Filter by Customer Phone (searches the related 'customers' table)
        if ($request->filled('customer_phone')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->customer_phone . '%');
            });
        }

        $sales = $query->latest()->paginate(20)->appends($request->query());

        return view('profit_report.index', compact('sales'));
    }

    /**
     * Display a detailed profit breakdown for a single sale.
     */
    public function show(Sale $sale)
    {
        $sale->load('customer', 'items.product.brand');
        return view('profit_report.show', compact('sale'));
    }
}