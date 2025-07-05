<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_type extends Model
{
    //use HasFactory;
    protected $guarded = [];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
