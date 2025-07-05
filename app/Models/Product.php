<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = 
    [
        'category_id', 
        'product_type_id', 
        'brand_id', 
        'model', 
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
