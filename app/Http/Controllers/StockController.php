<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Categorie;
use App\Models\ProductType;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with('product');

        // Filter by Product ID
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by Model
        if ($request->filled('model')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('model', 'like', '%' . $request->model . '%');
            });
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->whereHas('product.productType.category', function ($q) use ($request) {
                $q->where('id', $request->category_id);
            });
        }

        // Filter by Product Type
        if ($request->filled('product_type_id')) {
            $query->whereHas('product.productType', function ($q) use ($request) {
                $q->where('id', $request->product_type_id);
            });
        }

        $stocks = $query->paginate(5);
        $categories = Categorie::all();
        $productTypes = ProductType::all();
        $products = Product::all();

        return view('stocks.index', compact('stocks', 'categories', 'productTypes', 'products'));
    }

    public function getLSP($productId)
{
    $stock = Stock::where('product_id', $productId)->latest()->first();

    if (!$stock) {
        return response()->json(['lsp' => 0], 404);
    }

    return response()->json(['lsp' => $stock->lsp]);
}


}