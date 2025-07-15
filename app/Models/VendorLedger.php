<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorLedger extends Model
{
    protected $fillable = [
        'vendor_id',
        'purchase_id',
        'transaction_date',
        'description',
        'bill_by',
        'received_by',
        'payment_type',
        'notes',
        'debit',
        'credit',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    // public function scopeUnappliedAdvances($query)
    // {
    //     return $query->whereNull('purchase_id')
    //         ->where('description', 'Advance Payment')
    //         ->where('debit', 0)
    //         ->where('credit', '>', 0);
    // }
}
