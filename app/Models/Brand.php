<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'category_id',
        'product_type_id',
        'name',
    ];

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
}
