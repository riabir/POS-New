<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'customer_id',
        'transaction_type',
        'amount',
        'paid_amount',
        'due_date',
        'paid_date',
        'status',
    ];

    public function getBalanceAttribute()
    {
        return $this->amount - $this->paid_amount;
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}