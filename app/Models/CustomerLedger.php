<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // Add this
use Illuminate\Support\Str; // Add this

class CustomerLedger extends Model
{
    // Important: Add the new fields to your fillable property
    protected $fillable = [
        'customer_id',
        'sale_id',
        'transaction_id',
        'transaction_type',
        'transaction_date',
        'description',
        'bill_by',
        'received_by',
        'payment_type',
        'notes',
        'debit',  // Amount that DECREASES customer's due balance (Payments, Credits)
        'credit', // Amount that INCREASES customer's due balance (Invoices, Charges)
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function sale() { return $this->belongsTo(Sale::class); }

    // Remove the ledgers() relationship from here. It belongs on the Customer model.

    // Accessor for a nicely formatted transaction type
    protected function formattedType(): Attribute {
        return Attribute::make(get: fn () => str_replace('_', ' ', Str::title($this->transaction_type)));
    }

    // Accessor for color-coding the transaction types
    protected function typeColor(): Attribute {
        return Attribute::make(get: fn () => match ($this->transaction_type) {
            'invoice' => '#dc3545', // Red
            'new_payment' => '#198754', // Green
            'advance_payment' => '#28a745', // Lighter Green
            'advance_adjustment' => '#0dcaf0', // Cyan
            'vat_adjustment', 'tax_adjustment' => '#ffc107', // Yellow (use dark text)
            'other_adjustment' => '#6c757d', // Gray
            default => '#6c757d',
        });
    }
}