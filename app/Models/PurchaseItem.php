<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'unit_price',
        'quantity',
        'total_price',
        'warranty',
        'warranty_expiry_date',
        'serial_numbers',
    ];

    protected $casts = [
        'serial_numbers' => 'array',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
