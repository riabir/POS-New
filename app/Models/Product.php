<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // use HasFactory; // Uncomment if you use factories

    protected $fillable = [
        'category_id',
        'product_type_id',
        'brand_id',
        'model',
        'name',
        'description',
        // Add other fillable attributes as needed
    ];

    /**
     * Get the product type that this product belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Get the stock record associated with the product.
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

     public function latestPurchaseItem()
    {
        // This is a powerful Eloquent relationship that gets the single
        // record from the 'hasMany' relationship that is the "latest".
        return $this->hasOne(PurchaseItem::class)->latestOfMany();
    }
}
