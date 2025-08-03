<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Categorie;
use App\Models\ProductType;

class BrandController extends Controller
{
    /**
     * Display a listing of the brands with filtering.
     */
    public function index(Request $request)
    {
        $query = Brand::with(['category', 'productType'])->orderBy('name', 'asc');

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Product Type
        if ($request->filled('product_type_id')) {
            $query->where('product_type_id', $request->product_type_id);
        }

        // Filter by Brand Name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $brands = $query->paginate(15)->appends($request->query());
        
        $categories = Categorie::orderBy('name')->get();
        // We get product types dynamically, but can pass all for the filter
        $productTypes = ProductType::orderBy('name')->get();

        return view('brands.index', compact('brands', 'categories', 'productTypes'));
    }

    /**
     * Show the form for creating a new brand.
     */
    public function create()
    {
        $categories = Categorie::orderBy('name')->get();
        // Product types will be loaded via AJAX, so we don't need to pass them here.
        return view('brands.create', compact('categories'));
    }

    /**
     * Store a newly created brand in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'name' => 'required|string|max:255|unique:brands,name',
        ]);

        Brand::create($validated);

        return redirect()->route('brands.index')->with('success', 'New Brand created successfully!');
    }

    /**
     * Show the form for editing the specified brand.
     */
    public function edit(Brand $brand)
    {
        $categories = Categorie::all();
        // Get product types for the brand's current category to pre-populate the dropdown
        $productTypes = ProductType::where('category_id', $brand->category_id)->get();

        return view('brands.edit', compact('brand', 'categories', 'productTypes'));
    }

    /**
     * Update the specified brand in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'name' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('brands')->ignore($brand->id)],
        ]);
        
        $brand->update($validated);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    /**
     * Remove the specified brand from storage.
     */
    public function destroy(Brand $brand)
    {
         $brand->delete();
         return redirect()->route('brands.index')->with('success', 'Brand has been deleted.');
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
}