<?php

namespace App\Http\Controllers;
use App\Models\Vendor;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\PurchaseItem; 
use App\Models\Stock;
use App\Models\VendorAccount;
use App\Models\VendorLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class PurchaseController extends Controller
{
    // index, create, and other methods are unchanged...
    public function index()
    {
        $purchases = Purchase::with('vendor')->latest()->paginate(15);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $products = Product::all();
        return view('purchases.create', compact('products'));
    }

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
            'items.*.warranty' => 'nullable|integer|min:0', // +++ UPDATED VALIDATION KEY
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

            foreach ($validatedData['items'] as $item) {
                // +++ 2. ADD THE WARRANTY CALCULATION LOGIC HERE +++
                $warranty = $item['warranty'] ?? null;
                $expiryDate = null;
                
                // Calculate expiry date only if warranty Days are provided and greater than 0
                if ($warranty && is_numeric($warranty) && $warranty > 0) {
                    $expiryDate = $purchaseDate->copy()->addDays((int)$warranty)->toDateString();
                }

                // +++ 3. ADD THE CALCULATED VALUES TO THE CREATE METHOD +++
                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                    'serial_numbers' => $item['serial_number'],
                    'warranty' => $warranty, // <-- Save warranty duration
                    'warranty_expiry_date' => $expiryDate,   // <-- Save calculated expiry date
                ]);
                
                // Stock logic remains the same
                $stock = Stock::firstOrNew(['product_id' => $item['product_id']]);
                $stock->quantity = ($stock->quantity ?? 0) + $item['quantity'];
                $stock->serial_numbers = array_merge($stock->serial_numbers ?? [], $item['serial_number']);
                $stock->lsp = $item['unit_price'];
                $stock->save();
            }

            // Vendor Account and Ledger logic remains the same
            VendorAccount::create([
                'purchase_id' => $purchase->id,
                'vendor_id' => $purchase->vendor_id,
                'amount' => $purchase->grand_total,
                'due_date' => $purchaseDate->copy()->addDays(30),
                'status' => 'unpaid',
            ]);

            VendorLedger::create([
                'vendor_id' => $purchase->vendor_id,
                'purchase_id' => $purchase->id,
                'transaction_date' => $purchaseDate,
                'description' => 'Purchase on ' . $purchase->purchase_no,
                'bill_by' => Auth::user()->name,
                'credit' => $purchase->grand_total,
            ]);
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase created. Bill is now pending in Accounts.');
    }

    // destroy and searchvendor methods are unchanged...
    public function destroy(Purchase $purchase)
    {
        try {
            // This one line will trigger the 'deleting' event in the Purchase model.
            $purchase->delete();

            // If the deletion succeeds, redirect with a success message.
            return redirect()->route('purchases.index')
                             ->with('success', 'Purchase and all related records have been deleted successfully.');

        } catch (Exception $e) {
            // If the 'deleting' event threw an exception (e.g., stock too low),
            // catch it here and redirect back with the specific error message.
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

        // +++ FIX: Change the view name to match the blade file (remove underscore) +++
        return view('purchases.invoice_preview', compact('purchase'));
    }
 
}
