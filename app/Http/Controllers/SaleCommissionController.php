<?php

namespace App\Http\Controllers;

use App\Models\SaleCommission;
use Illuminate\Http\Request;

class SaleCommissionController extends Controller
{
    /**
     * Display a listing of all sale commissions with filtering.
     */
    public function index(Request $request)
    {
        $query = SaleCommission::with(['sale', 'recipient'])->latest();

        // --- FILTERING LOGIC ---

        // Filter by Date Range: From
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Filter by Date Range: To
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by Sale Bill Number
        if ($request->filled('bill_no')) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->where('bill_no', 'like', '%' . $request->bill_no . '%');
            });
        }
        
        // Filter by Recipient Type (Customer or Shareholder)
        if ($request->filled('recipient_type')) {
            $query->where('recipient_type', $request->recipient_type);
        }
        
        // Filter by Recipient Name
        if ($request->filled('recipient_name')) {
            // This is a polymorphic search, we need to check both possible tables
            $query->whereHasMorph('recipient', ['App\Models\Customer', 'App\Models\Shareholder'], function ($q, $type) use ($request) {
                if ($type === 'App\Models\Customer') {
                    $q->where('customer_name', 'like', '%' . $request->recipient_name . '%');
                }
                if ($type === 'App\Models\Shareholder') {
                    $q->where('name', 'like', '%' . $request->recipient_name . '%');
                }
            });
        }
        
        // Filter by Recipient Phone
        if ($request->filled('recipient_phone')) {
            $query->whereHasMorph('recipient', ['App\Models\Customer', 'App\Models\Shareholder'], function ($q) use ($request) {
                // Both tables use a 'phone' column, so this is simpler
                $q->where('phone', 'like', '%' . $request->recipient_phone . '%');
            });
        }
        
        // Filter by Recipient ID
        if ($request->filled('recipient_id')) {
            $query->where('recipient_id', $request->recipient_id);
        }
        
        // Execute the query
        $commissions = $query->paginate(20)->appends($request->query());

        return view('sale_commissions.index', compact('commissions'));
    }

    /**
     * Display the specified commission record.
     */
    public function show(SaleCommission $commission)
    {
        $commission->load(['sale', 'recipient']);
        return view('sale_commissions.show', compact('commission'));
    }
}