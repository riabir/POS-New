<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    // use HasFactory; // Uncomment if you use factories

    protected $fillable = [
        'product_id',
        'quantity',
        'lsp', // Last Purchase Price
        'serial_numbers',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'serial_numbers' => 'array',
    ];

    /**
     * Get the product that this stock record belongs to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}