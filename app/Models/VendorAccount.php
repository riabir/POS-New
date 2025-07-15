<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'vendor_id',
        'transaction_type',
        'amount',
        'paid_amount',
        'due_date',
        'paid_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'datetime',
    ];


    public function getBalanceAttribute()
    {
        return $this->amount - $this->paid_amount;
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}