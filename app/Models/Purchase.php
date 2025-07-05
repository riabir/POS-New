<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'vendor_id', 'purchase_no', 'purchase_date', 'remarks', 'product_id', 'p_sn', 'unit_price', 
        'quantity', 'total_price'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
