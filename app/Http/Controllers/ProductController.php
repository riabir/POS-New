<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categorie;
use App\Models\Brand;
use App\Models\ProductType;

class ProductController extends Controller
{
    
    public function index(Request $request)
{
    // Start with the base query and eager load relationships
    $query = Product::with(['category', 'productType', 'brand'])->latest();

    // --- FILTERING LOGIC ---

    // Filter by Brand
    if ($request->filled('brand_id')) {
        $query->where('brand_id', $request->brand_id);
    }
    
    // Filter by Model
    if ($request->filled('model')) {
        $query->where('model', 'like', '%' . $request->model . '%');
    }

    // Filter by the combined "Product ID" (Category.Type.Brand.ID)
    if ($request->filled('product_id_string')) {
        // Since this is a concatenated string and not a real column,
        // we use a raw WHERE clause to build and search it.
        // CONCAT_WS safely handles dots between parts.
        $query->whereRaw('CONCAT_WS(".", category_id, product_type_id, brand_id, id) LIKE ?', ["%{$request->product_id_string}%"]);
    }

    // --- END OF FILTERING LOGIC ---

    // Execute the query, paginate, and append filter params to links
    $products = $query->paginate(15)->appends($request->query());
    
    // We also need to pass the list of brands to the view for the filter dropdown
    $brands = Brand::orderBy('name')->get();

    return view('products.index', compact('products', 'brands'));
}

    // =====================================================================
    //  THIS METHOD WAS ALSO MISSING
    // =====================================================================
    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Categorie::all();
        $brands = Brand::all();
        return view('products.create', compact('categories','brands'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'brand_id' => 'required|exists:brands,id',
            'model' => 'required|string|max:255',
            'header' => 'required|string',
            'mrp' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'specifications.*.key' => 'nullable|string',
            'specifications.*.value' => 'nullable|string',
        ]);

        $specsFormatted = [];
        if ($request->filled('specifications')) {
            foreach ($request->specifications as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $specsFormatted[$spec['key']] = $spec['value'];
                }
            }
        }
        $validated['specifications'] = $specsFormatted;

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'productType', 'brand']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Categorie::all();
        $brands = Brand::all();
        $productTypes = ProductType::where('category_id', $product->category_id)->get();
        return view('products.edit', compact('product','categories','brands', 'productTypes'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'brand_id' => 'required|exists:brands,id',
            'model' => 'required|string|max:255',
            'header' => 'required|string',
            'mrp' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'specifications.*.key' => 'nullable|string',
            'specifications.*.value' => 'nullable|string',
        ]);

        $specsFormatted = [];
        if ($request->has('specifications')) {
            foreach ($request->specifications as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $specsFormatted[$spec['key']] = $spec['value'];
                }
            }
        }
        $validated['specifications'] = $specsFormatted;

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
         $product->delete();
         return redirect()->route('products.index')->with('success', 'Product has been deleted.');
    }
    
    /**
     * Get Product Types for a given category via AJAX.
     */
    public function getProductTypes(Request $request)
    {
        $id = $request->id;
        $productTypes = ProductType::where('category_id', $id)->get();
        return response()->json($productTypes);
    }

    /**
     * Search for products (likely for an autocomplete feature).
     */
    public function search(Request $request)
    {
        $term = $request->get('term');
        $products = Product::where('model', 'LIKE', "%{$term}%")->orWhere('header', 'LIKE', "%{$term}%")->pluck('model');
        return response()->json($products);
    }
}