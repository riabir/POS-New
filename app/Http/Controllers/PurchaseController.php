<?php

namespace App\Http\Controllers;
use App\Models\Vendor;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Laravel\Pail\ValueObjects\Origin\Console;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::all();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $purchases = Purchase::all();
        $products = Product::all();
        return view('purchases.create', compact('purchases','products'));
    }

    public function searchvendor(Request $request)
    {
       $id = $request->id;
       
       $vendor = Vendor::where('phone',$id)->first();
       return $vendor;

        if ($vendor) {
            return response()->json([
                'vendor_name' => $vendor->vendor_name,
                'email' => $vendor->email,
                'address' => $vendor->address,
            ]);
        } else {
            return response()->json(['message' => 'Vendor not found'], 404);
        }
    }


    public function searchproduct(Request $request)
    {
       $id = $request->id;

       $product = Product::where('model', 'LIKE', "{$id}%")->get();
       return $product;

        // if ($product) {
        //     return response()->json([
        //         'vendor_id' => $product->vendor_id,
        //         'product_type_id' => $product->product_type_id,
        //         'brand_id' => $product->brand_id,
        //         'model' => $product->model,
        //         'description' => $product->description,
        //     ]);
        // } else {
        //     return response()->json(['message' => 'Product not found'], 404);
        // }
    }
}
