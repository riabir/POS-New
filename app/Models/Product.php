<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'product_type_id',
        'brand_id',
        'model',
        'header',       // The full product title
        'description',  // The marketing paragraph
        'mrp',          // The selling price
        'specifications',// The key-value technical details
    ];

    // =====================================================================
    //  THIS IS THE CRUCIAL MISSING PIECE
    // =====================================================================
    /**
     * The attributes that should be cast.
     * This tells Laravel to automatically convert the 'specifications'
     * array to JSON when saving, and back to an array when retrieving.
     *
     * @var array
     */
    protected $casts = [
        'specifications' => 'array',
    ];
    // =====================================================================

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
        return $this->hasOne(PurchaseItem::class)->latestOfMany();
    }
}