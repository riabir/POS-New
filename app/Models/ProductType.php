<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
   protected $fillable = 
    [
        'category_id', 
        'name', 
    ];

    protected $guarded = [];
    
   public function category()
{
    return $this->belongsTo(Categorie::class, 'category_id');
}

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
