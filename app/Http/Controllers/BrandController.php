<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Categorie;
use App\Models\ProductType;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        $categories = Categorie::all();
        $product_types = ProductType::all();
        return view('brands.index', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'name' => 'required|string|max:255',
        ]);

        Brand::create($validated);

        return redirect()->route('brands.index')->with('success', 'New Brand created successfully!');
    }

    public function getProductTypes(Request $request)
    {
        $id = $request->id;
        $productTypes = ProductType::where('category_id', $id)->get();
        return response()->json($productTypes);
    }

    // Edit File
    public function edit($id){
        $brands= Brand::find($id);
        $categories = Categorie::all();
        return view('brands.edit', compact('brands','categories'));
    }

     //update file   
      public function update($id, Request $request)
    {
        //return $request->all();
        $brand= Brand::find($id);
        $brand->category_id=$request->category_id;
        $brand->product_type_id=$request->product_type_id;
        $brand->name=$request->name;
        $brand->save();
        return redirect()->route('brands.index')->with('success', 'Brand Update successfully.');
    }

    //Delete file
        public function destroy($id){
         $brand= Brand::find($id);
         $brand->delete();
         return redirect()->route('brands.index')->with('success', 'Brand has been deleted.');
    }

}
