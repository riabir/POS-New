<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Categorie extends Model
{
    protected $fillable = ['name'];

    /**
     * Get all of the product types for the category.
     */
    public function productTypes()
    {
        return $this->hasMany(ProductType::class, 'category_id');
    }

    /**
     * Get all of the products for the category through product types.
     * This defines the relationship: Category -> has many -> Products THROUGH ProductType
     */
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, ProductType::class);
    }
}