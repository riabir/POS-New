<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Session\Store;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'unit_price',
        'cost_price', 
        'quantity',
        'total_price',
        'warranty',
        'warranty_expiry_date',
        'serial_numbers',
    ];

    protected $casts = [
        'serial_numbers' => 'array',
    ];

    public function Sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
