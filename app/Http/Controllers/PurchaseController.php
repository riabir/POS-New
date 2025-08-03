<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\Stock;
use App\Models\VendorAccount;
use App\Models\VendorLedger;
use Illuminate\Http\Request; // <-- Make sure Request is imported
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     * MODIFIED to include filtering capabilities.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Start with the base query, including the vendor relationship to avoid N+1 problems
        $query = Purchase::with('vendor')->latest();

        // --- FILTERING LOGIC ---

        // Filter by Vendor Phone Number
        if ($request->filled('phone')) {
            $query->whereHas('vendor', function ($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->phone . '%');
            });
        }

        // Filter by Purchase Order (PO) Number
        if ($request->filled('purchase_no')) {
            $query->where('purchase_no', 'like', '%' . $request->purchase_no . '%');
        }

        // Filter by Purchase Date
        if ($request->filled('date')) {
            $query->whereDate('purchase_date', $request->date);
        }

        // Filter by Product Model
        if ($request->filled('model')) {
            // This is a nested relationship: Purchase -> items -> product
            $query->whereHas('items.product', function ($q) use ($request) {
                $q->where('model', 'like', '%' . $request->model . '%');
            });
        }

        // Execute the query and paginate the results
        // IMPORTANT: Append the query string to pagination links to keep filters active
        $purchases = $query->paginate(15)->appends($request->query());

        return view('purchases.index', compact('purchases'));
    }

    // ... all your other methods (create, store, destroy, showPreview, etc.) remain unchanged ...
    public function create()
    {
        $products = Product::all();
        return view('purchases.create', compact('products'));
    }
    
    // ... store, destroy, searchvendor, showPreview methods are fine ...
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'remarks' => 'nullable|string',
            'sub_total' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.total_price' => 'required|numeric|min:0',
            'items.*.warranty' => 'nullable|integer|min:0',
            'items.*.serial_number' => 'required|array',
            'items.*.serial_number.*' => 'required|string|max:255|distinct',
        ]);
        
        DB::transaction(function () use ($validatedData) {
            $last = Purchase::latest('id')->first();
            $nextNo = 'PO-' . str_pad(($last ? $last->id + 1 : 1), 5, '0', STR_PAD_LEFT);
            $purchaseDate = now();
            
            $purchase = Purchase::create([
                'vendor_id' => $validatedData['vendor_id'],
                'purchase_no' => $nextNo,
                'purchase_date' => $purchaseDate,
                'remarks' => $validatedData['remarks'],
                'sub_total' => $validatedData['sub_total'],
                'discount' => $validatedData['discount'],
                'grand_total' => $validatedData['grand_total'],
            ]);
            
            // Create vendor account record
            VendorAccount::create([
                'purchase_id' => $purchase->id,
                'vendor_id' => $purchase->vendor_id,
                'amount' => $purchase->grand_total,
                'due_date' => $purchaseDate->copy()->addDays(30),
                'status' => 'unpaid',
            ]);
            
            // Create ledger entry for the purchase (liability account)
            VendorLedger::create([
                'vendor_id' => $purchase->vendor_id,
                'purchase_id' => $purchase->id,
                'transaction_date' => $purchaseDate,
                'description' => 'Purchase on ' . $purchase->purchase_no,
                'bill_by' => Auth::user()->name,
                'debit' => 0,
                'credit' => $purchase->grand_total,
                'account_type' => 'liability',
                'transaction_type' => 'purchase',
            ]);
            
            foreach ($validatedData['items'] as $item) {
                $warranty = $item['warranty'] ?? null;
                $expiryDate = null;
                if ($warranty && is_numeric($warranty) && $warranty > 0) {
                    $expiryDate = $purchaseDate->copy()->addDays((int)$warranty)->toDateString();
                }
                
                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                    'serial_numbers' => $item['serial_number'],
                    'warranty' => $warranty,
                    'warranty_expiry_date' => $expiryDate,
                ]);
                
                $stock = Stock::firstOrNew(['product_id' => $item['product_id']]);
                $stock->quantity = ($stock->quantity ?? 0) + $item['quantity'];
                $stock->serial_numbers = array_merge($stock->serial_numbers ?? [], $item['serial_number']);
                $stock->lsp = $item['unit_price'];
                $stock->save();
            }
        });
        
        return redirect()->route('purchases.index')->with('success', 'Purchase created. Bill is now pending in Accounts.');
    }
    

    public function destroy(Purchase $purchase)
    {
        try {
            $purchase->delete();
            return redirect()->route('purchases.index')
                             ->with('success', 'Purchase and all related records have been deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['deletion_error' => $e->getMessage()]);
        }
    }

    public function searchvendor($phone)
    {
        $vendor = Vendor::where('phone', $phone)->first();
        return $vendor
            ? response()->json($vendor)
            : response()->json(['message' => 'Vendor not found'], 404);
    }

    public function showPreview(Purchase $purchase)
    {
        $purchase->load('vendor', 'items.product.brand');
        return view('purchases.invoice_preview', compact('purchase'));
    }
}