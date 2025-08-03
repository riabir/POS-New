<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;
use App\Models\Categorie;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource with filtering.
     */
    public function index(Request $request)
    {
        // Start a query builder instance, eager loading the category for performance
        $query = ProductType::with('category')->orderBy('name', 'asc');

        // --- FILTERING LOGIC ---

        // Filter by Product Type ID
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        // Filter by Parent Category ID
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Product Type Name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // --- END OF FILTERING LOGIC ---

        // Get the paginated results and append the filter query string
        $product_types = $query->paginate(10)->appends($request->query());

        // We still need all categories for the filter and modal dropdowns
        $categories = Categorie::orderBy('name')->get();
        

        return view('product_types.index', compact('categories', 'product_types'));
    }

    // ... The rest of your controller (store, edit, update, destroy) is fine ...

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);
        ProductType::create($validated);
        return redirect()->route('product_types.index')->with('success', 'Product Type created successfully!');
    }

    public function edit($id)
    {
        $product_type= ProductType::find($id);
        $categories = Categorie::all();
        return view('product_types.edit', compact('product_type','categories'));
    }
    
    public function update($id, Request $request)
    {
        $product_type= ProductType::find($id);
        $product_type->category_id=$request->category_id;
        $product_type->name=$request->name;
        $product_type->save();
        return redirect()->route('product_types.index')->with('success', 'Product Type updated successfully.');
    }

    public function destroy($id)
    {
         $product_type= ProductType::find($id);
         $product_type->delete();
         return redirect()->route('product_types.index')->with('success', 'Product Type has been deleted.');
    }
}