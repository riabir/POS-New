<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;
use App\Models\Categorie;

class ProductTypeController extends Controller
{
   
    public function index()
    {
        // WRONG WAY (This returns a Collection)
        $categories = Categorie::latest()->paginate(10);
        $product_types = ProductType::latest()->paginate(10);
        return view('product_types.index', compact('categories','product_types'));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        ProductType::create($validated);

        return redirect()->route('product_types.index')->with('success', 'Product Type created successfully!');
    }


    // Edit File
    public function edit($id){
        $product_type= ProductType::find($id);
        //return $product;
        $categories = Categorie::all();
        return view('product_types.edit', compact('product_type','categories'));
    }
    
    //update file   
      public function update($id, Request $request)
    {
        //return $request->all();
        $product_type= ProductType::find($id);
        $product_type->category_id=$request->category_id;
        $product_type->name=$request->name;
        $product_type->save();
        return redirect()->route('product_types.index')->with('success', 'Product Type created successfully.');
    }

      //Delete file
        public function destroy($id){
         $product_type= ProductType::find($id);
         $product_type->delete();
         return redirect()->route('product_types.index')->with('success', 'Produt Type has been deleted.');
    }

}