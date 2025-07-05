<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categorie;
use App\Models\Brand;
use App\Models\ProductType;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Categorie::all();
        $brands = Brand::all();
        return view('products.create', compact('categories','brands'));
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'brand_id' => 'required|exists:brands,id',
            'model' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Item created successfully!');
    }

     public function getProductTypes(Request $request)
    {
        $id = $request->id;
        $productTypes = ProductType::where('category_id', $id)->get();
        return response()->json($productTypes);
    }


    // Edit File
    public function edit($id){
        $product= Product::find($id);
        //return $product;
        $categories = Categorie::all();
        $brands = Brand::all();
        return view('products.edit', compact('product','categories','brands'));
    }

    //update file   
      public function update($id, Request $request)
    {
        //return $request->all();
        $product= Product::find($id);
        $product->category_id=$request->category_id;
        $product->product_type_id=$request->product_type_id;
        $product->brand_id=$request->brand_id;
        $product->model=$request->model;
        $product->description=$request->description;
        $product->save();
        return redirect()->route('products.index')->with('success', 'Item created successfully.');
    }

    //Delete file
        public function destroy($id){
         $product= Product::find($id);
         $product->delete();
         return redirect()->route('products.index')->with('success', 'Item has been deleted.');
    }

}

