<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Categorie;
use App\Models\ProductType;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with(['product.category', 'product.productType', 'product.brand']);

        // Filter by Product ID string (e.g., "10.12.5.15")
        if ($request->filled('product_id_string')) {
            // Split the string by the dot into an array of parts
            $id_parts = explode('.', $request->product_id_string);
            
            // Assign parts to variables for clarity, using null coalescing for safety
            $categoryId = $id_parts[0] ?? null;
            $productTypeId = $id_parts[1] ?? null;
            $brandId = $id_parts[2] ?? null;
            $productId = $id_parts[3] ?? null;

            // Apply filters to the product relationship
            $query->whereHas('product', function ($q) use ($categoryId, $productTypeId, $brandId, $productId) {
                if ($categoryId) {
                    $q->where('category_id', $categoryId);
                }
                if ($productTypeId) {
                    $q->where('product_type_id', $productTypeId);
                }
                if ($brandId) {
                    $q->where('brand_id', $brandId);
                }
                if ($productId) {
                    $q->where('id', $productId);
                }
            });
        }

        // --- Other filters remain the same ---
        if ($request->filled('model')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('model', 'like', '%' . $request->model . '%');
            });
        }
        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }
        if ($request->filled('product_type_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('product_type_id', $request->product_type_id);
            });
        }

        $stocks = $query->latest('updated_at')->paginate(20);

        // This logic for dependent dropdowns is still correct
        $categories = Categorie::orderBy('name')->get();
        $productTypes = collect();
        if ($request->filled('category_id')) {
            $productTypes = ProductType::where('category_id', $request->category_id)
                                        ->orderBy('name')
                                        ->get();
        }

        return view('stocks.index', compact('stocks', 'categories', 'productTypes'));
    }

    // ... your getLSP method is fine ...
    public function getLSP($productId)
    {
        $stock = Stock::where('product_id', $productId)->latest()->first();
        if (!$stock) {
            return response()->json(['lsp' => 0], 404);
        }
        return response()->json(['lsp' => $stock->lsp]);
    }
}