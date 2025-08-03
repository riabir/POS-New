<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Stock;
use App\Models\PurchaseItem;
use App\Models\CustomerAccount;
use App\Models\CustomerLedger;
use App\Models\SaleCommission;
use App\Models\Shareholder;
use App\Models\ShareholderTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Exception; // <-- Added for try-catch block

class SaleController extends Controller
{
    /**
     * Display a listing of the resource with filtering.
     */
    public function index(Request $request)
    {
        $query = Sale::with('customer')->latest();
        if ($request->filled('phone')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->phone . '%');
            });
        }
        if ($request->filled('bill_no')) {
            $query->where('bill_no', 'like', '%' . $request->bill_no . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('bill_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('bill_date', '<=', $request->date_to);
        }
        if ($request->filled('model')) {
            $query->whereHas('items.product', function ($q) use ($request) {
                $q->where('model', 'like', '%' . $request->model . '%');
            });
        }
        $sales = $query->paginate(15)->appends($request->query());
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::with('stock', 'latestPurchaseItem')->orderBy('model')->get();
        return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage, including cost price for profit calculation.
     */
    public function store(Request $request)
    {
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
            'items.*.warranty' => 'nullable|integer|min:0',
            'items.*.serial_number' => 'required|array',
            'items.*.serial_number.*' => 'required|string|max:255|distinct',
            'commission_amount' => 'nullable|numeric|min:0',
            'commission_recipient_id' => 'required_with:commission_amount|nullable|integer',
            'commission_recipient_type' => 'required_with:commission_amount|nullable|string|in:App\Models\Customer,App\Models\Shareholder',
        ]);

        DB::transaction(function () use ($validatedData, $request) {
            $saleDate = now();
            $sale = Sale::create([
                'customer_id' => $validatedData['customer_id'],
                'bill_no' => 'TEMP',
                'bill_date' => $saleDate,
                'remarks' => $validatedData['remarks'],
                'sub_total' => $validatedData['sub_total'],
                'discount' => $validatedData['discount'],
                'grand_total' => $validatedData['grand_total'],
                'status' => 'due'
            ]);
            $sale->bill_no = 'B-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT);
            $sale->save();

            foreach ($validatedData['items'] as $item) {
                $stock = Stock::where('product_id', $item['product_id'])->lockForUpdate()->first();
                if (!$stock || $stock->quantity < $item['quantity']) {
                    throw ValidationException::withMessages(['items' => 'Not enough stock for one of the selected products.']);
                }
                $existingSerials = $stock->serial_numbers ?? [];
                $soldSerials = $item['serial_number'];
                $invalidSerials = array_diff($soldSerials, $existingSerials);
                if (!empty($invalidSerials)) {
                    throw ValidationException::withMessages(['items' => 'Invalid serial number(s) provided: ' . implode(', ', $invalidSerials)]);
                }

                $costPrice = 0;
                $firstSerial = $item['serial_number'][0] ?? null;
                if ($firstSerial) {
                    $purchaseItem = PurchaseItem::whereJsonContains('serial_numbers', $firstSerial)->first();
                    if ($purchaseItem) {
                        $costPrice = $purchaseItem->unit_price;
                    }
                }
                if ($costPrice == 0) {
                    $costPrice = $stock->lsp ?? 0;
                }

                $warrantyDuration = $item['warranty'] ?? null;
                $expiryDate = ($warrantyDuration > 0) ? $saleDate->copy()->addDays((int) $warrantyDuration)->toDateString() : null;

                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'unit_price' => $item['unit_price'],
                    'cost_price' => $costPrice,
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                    'serial_numbers' => $item['serial_number'],
                    'warranty' => $warrantyDuration,
                    'warranty_expiry_date' => $expiryDate,
                ]);
                $stock->quantity -= $item['quantity'];
                $stock->serial_numbers = array_values(array_diff($existingSerials, $soldSerials));
                $stock->save();
            }

            if ($request->filled('commission_amount') && $request->commission_amount > 0) {
                $sale->commissions()->create([
                    'recipient_id'   => $validatedData['commission_recipient_id'],
                    'recipient_type' => $validatedData['commission_recipient_type'],
                    'amount'         => $validatedData['commission_amount'],
                    'notes'          => 'Commission from Sale #' . $sale->bill_no,
                ]);
                $commissionAmount = $validatedData['commission_amount'];
                $description = 'Commission received for sale ' . $sale->bill_no;
                if ($validatedData['commission_recipient_type'] === 'App\\Models\\Customer') {
                    CustomerLedger::create([
                        'customer_id'      => $validatedData['commission_recipient_id'],
                        'sale_id'          => $sale->id,
                        'transaction_id'   => 'COM-' . $sale->id,
                        'transaction_type' => 'commission_payment',
                        'transaction_date' => $sale->bill_date,
                        'description'      => $description,
                        'debit'            => 0,
                        'credit'           => $commissionAmount,
                        'bill_by'          => Auth::user()->name,
                    ]);
                } elseif ($validatedData['commission_recipient_type'] === 'App\\Models\\Shareholder') {
                    ShareholderTransaction::create([
                        'shareholder_id'   => $validatedData['commission_recipient_id'],
                        'transaction_date' => $sale->bill_date,
                        'type'             => 'Commission',
                        'amount'           => $commissionAmount,
                        'description'      => $description,
                    ]);
                }
            }

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
                'transaction_type' => 'sale',
                'transaction_date' => $sale->bill_date,
                'description' => 'Invoice for Bill No: ' . $sale->bill_no,
                'debit' => $sale->grand_total,
                'credit' => 0,
                'bill_by' => Auth::user()->name,
                'account_type' => 'liability',
            ]);
        });

        return redirect()->route('sales.index')->with('success', 'Sale created successfully. Stock, accounts, and commission updated.');
    }

    public function edit(Sale $sale)
    {
        $products = Product::with('stock', 'latestPurchaseItem')->orderBy('model')->get();
        $sale->load('items.product', 'customer');
        return view('sales.edit', compact('sale', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'remarks' => 'nullable|string',
            'discount' => 'required|numeric|min:0|max:' . $sale->sub_total,
        ]);

        $newGrandTotal = $sale->sub_total - $validated['discount'];

        DB::transaction(function () use ($sale, $validated, $newGrandTotal) {
            $sale->update([
                'customer_id' => $validated['customer_id'],
                'remarks' => $validated['remarks'],
                'discount' => $validated['discount'],
                'grand_total' => $newGrandTotal,
            ]);

            $customerAccount = CustomerAccount::where('sale_id', $sale->id)->first();
            if ($customerAccount) {
                $customerAccount->update([
                    'customer_id' => $validated['customer_id'],
                    'amount' => $newGrandTotal,
                ]);
            }
        });

        return redirect()->route('sales.index')->with('success', "Sale {$sale->bill_no} updated successfully.");
    }

    public function show(Sale $sale)
    {
        $sale->load('customer', 'items.product.brand');
        return view('sales.show', compact('sale'));
    }

    public function showPreview(Sale $sale)
    {
        $sale->load('customer', 'items.product.brand');
        return view('sales.invoice_preview', compact('sale'));
    }

    /**
     * Remove the specified resource from storage.
     * The complex deletion logic is now handled by the Sale model's 'deleting' event observer.
     */
    public function destroy(Sale $sale)
    {
        try {
            // This single line triggers the 'deleting' event in the Sale model.
            // The model is now responsible for returning stock, deleting commissions,
            // and reversing all ledger entries in a single database transaction.
            $sale->delete();

            return redirect()->route('sales.index')
                ->with('success', 'Sale and all related records have been deleted successfully. Stock and ledgers have been updated.');
        } catch (Exception $e) {
            // This will catch any exceptions if the database transaction in the model fails.
            return redirect()->back()
                ->withErrors(['deletion_error' => 'An error occurred while deleting the sale: ' . $e->getMessage()]);
        }
    }

    public function searchcustomer($phone)
    {
        $customer = Customer::where('phone', $phone)->first();
        return $customer
            ? response()->json($customer)
            : response()->json(['message' => 'Customer not found'], 404);
    }

    public function searchRecipient($phone)
    {
        $customer = Customer::where('phone', $phone)->first();
        if ($customer) {
            return response()->json([
                'id' => $customer->id,
                'name' => $customer->customer_name,
                'type' => 'App\\Models\\Customer',
            ]);
        }

        $shareholder = Shareholder::where('phone', $phone)->first();
        if ($shareholder) {
            return response()->json([
                'id' => $shareholder->id,
                'name' => $shareholder->name,
                'type' => 'App\\Models\\Shareholder',
            ]);
        }

        return response()->json(['message' => 'Recipient not found'], 404);
    }
}