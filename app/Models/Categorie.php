<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    //use HasFactory;

    protected $fillable = ['name'];

    public function productTypes()
    {
        return $this->hasMany(ProductType::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}