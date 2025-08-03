<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class CustomerLedger extends Model
{
    use HasFactory;
    
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
        'debit',
        'credit',
        'account_type', // 'asset' or 'liability'
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Accessor for formatted transaction type
    protected function formattedType(): Attribute
    {
        return Attribute::make(
            get: fn () => str_replace('_', ' ', Str::title($this->transaction_type))
        );
    }

    // Accessor for color-coding transaction types
    protected function typeColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->transaction_type) {
                'sale' => '#dc3545', // Red for sales (debits)
                'payment' => '#198754', // Green for payments (credits)
                'advance' => '#28a745', // Lighter Green for advances (credits)
                'advance_adjustment' => '#0dcaf0', // Cyan for advance adjustments
                'vat_adjustment', 'tax_adjustment' => '#ffc107', // Yellow for adjustments
                'other_adjustment' => '#6c757d', // Gray for other adjustments
                default => '#6c757d',
            }
        );
    }
}