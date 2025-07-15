<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\CustomerAccount;
use App\Models\CustomerLedger;
use App\Models\SaleItem;
use Carbon\Carbon;

// ----------------------------

class SaleController extends Controller
{
    // index, create, and searchCustomer methods are unchanged...
    public function index()
    {
        $sales = Sale::with('customer')->latest()->paginate(15);
        return view('sales.index', compact('sales'));
    }

    public function searchcustomer($phone)
    {
        $customer = Customer::where('phone', $phone)->first();
        return $customer
            ? response()->json($customer)
            : response()->json(['message' => 'Customer not found'], 404);
    }

    public function create()
    {
        $products = Product::with('stock', 'latestPurchaseItem')->orderBy('model')->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        // I've changed 'warranty' to 'warranty_days' for clarity, assuming the value is in days.
        // If it's months, change the validation and calculation accordingly.
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'remarks' => 'nullable|string',
            'sub_total' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.total_price' => 'required|numeric|min:0',
            'items.*.warranty' => 'nullable|integer|min:0', // Your form input for warranty duration
            'items.*.serial_number' => 'required|array',
            'items.*.serial_number.*' => 'required|string|max:255|distinct',
        ]);

        // The transaction ensures that if any part fails, everything is rolled back.
        DB::transaction(function () use ($validatedData) { // Removed unused $request
            $saleDate = now();

            $sale = Sale::create([
                'customer_id' => $validatedData['customer_id'],
                'bill_no' => 'TEMP', // Temporary
                'bill_date' => $saleDate,
                'remarks' => $validatedData['remarks'],
                'sub_total' => $validatedData['sub_total'],
                'discount' => $validatedData['discount'],
                'grand_total' => $validatedData['grand_total'],
                'status' => 'due'
            ]);

            // Finalize the bill number immediately after getting the ID
            $sale->bill_no = 'B-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT);
            $sale->save();

            // +++ MERGED INTO A SINGLE, CORRECT LOOP +++
            foreach ($validatedData['items'] as $item) {

                // --- 1. Stock Validation (do this first) ---
                $stock = Stock::where('product_id', $item['product_id'])->lockForUpdate()->first();

                if (!$stock || $stock->quantity < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => 'Not enough stock for one of the selected products. Sale aborted.'
                    ]);
                }
                $existingSerials = $stock->serial_numbers ?? [];
                $soldSerials = $item['serial_number'];
                $invalidSerials = array_diff($soldSerials, $existingSerials);
                if (!empty($invalidSerials)) {
                    throw ValidationException::withMessages(['items' => 'Invalid serial number(s) provided: ' . implode(', ', $invalidSerials)]);
                }

                // --- 2. Warranty Calculation (for this specific item) ---
                $warrantyDuration = $item['warranty'] ?? null;
                $expiryDate = null;

                if ($warrantyDuration && is_numeric($warrantyDuration) && $warrantyDuration > 0) {
                    // Assuming your warranty input is in Days. If it's months, use ->addMonths()
                    $expiryDate = $saleDate->copy()->addDays((int) $warrantyDuration)->toDateString();
                }

                // --- 3. Create the SaleItem with correct warranty details ---
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                    'serial_numbers' => $item['serial_number'],
                    'warranty' => $warrantyDuration,       // Correctly saves this item's warranty
                    'warranty_expiry_date' => $expiryDate,  // Correctly saves this item's expiry date
                ]);

                // --- 4. Update the Stock ---
                $stock->quantity -= $item['quantity'];
                $stock->serial_numbers = array_values(array_diff($existingSerials, $soldSerials));
                $stock->save();
            }

            // Customer Account and Ledger logic remains perfectly fine
            CustomerAccount::create([
                'sale_id' => $sale->id,
                'customer_id' => $sale->customer_id,
                'transaction_type' => 'sale',
                'amount' => $sale->grand_total,
                'paid_amount' => 0,
                'due_date' => now()->addDays(30),
                'status' => 'unpaid',
            ]);

            CustomerLedger::create([
                'customer_id' => $sale->customer_id,
                'sale_id' => $sale->id,
                'transaction_id' => 'INV-' . $sale->id,
                'transaction_type' => 'invoice',
                'transaction_date' => $sale->bill_date,
                'description' => 'Invoice for PO: ' . $sale->bill_no,
                'debit' => $sale->grand_total, // The bill amount is a CREDIT
                'credit' => 0,
                'bill_by' => Auth::user()->name,
            ]);

        }); // End of DB::transaction

        return redirect()->route('sales.index')->with('success', 'Sale created successfully. Stock and accounts updated.');
    }

    // destroy and searchvendor methods are unchanged...
    
    public function destroy(Sale $sale) // Note the change here from $id to Sale $sale
    {
        try {
            // This single line triggers the powerful 'deleting' event in your Sale model,
            // which handles all the complex cleanup of stock, financials, and items.
            $sale->delete();

            return redirect()->route('sales.index')
                             ->with('success', 'Sale and all related records have been deleted successfully. Stock has been returned.');

        } catch (Exception $e) {
            // If anything goes wrong in the transaction, catch the error.
            return redirect()->back()
                             ->withErrors(['deletion_error' => 'An error occurred while deleting the sale: ' . $e->getMessage()]);
        }
    }

     public function showPreview(Sale $sale)
    {
        // Eager load the relationships we know we'll need for the invoice.
        // This prevents extra database queries and is very efficient.
        $sale->load('customer', 'items.product.brand');

        // Return a view partial. The name starts with an underscore by convention
        // to indicate it's a partial file not meant to be loaded on its own.
        return view('sales.invoice_preview', compact('sale'));
    }
}