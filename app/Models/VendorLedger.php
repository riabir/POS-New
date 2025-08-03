<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

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
        'account_type', // 'asset' or 'liability'
        'transaction_type', // 'advance', 'purchase', 'payment', 'application', 'refund_advance', 'refund_payable'
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

    /**
     * Get the formatted transaction type.
     */
    protected function formattedType(): Attribute
    {
        return Attribute::make(
            get: fn () => str_replace('_', ' ', Str::title($this->transaction_type))
        );
    }

    /**
     * Get the color for the transaction type.
     */
    protected function typeColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->transaction_type) {
                'advance' => '#28a745', // Green
                'purchase' => '#dc3545', // Red
                'payment' => '#198754', // Dark Green
                'application' => '#0dcaf0', // Cyan
                'refund_advance' => '#fd7e14', // Orange
                'refund_payable' => '#6f42c1', // Purple
                default => '#6c757d', // Gray
            }
        );
    }

    /**
     * Get the account type label.
     */
    protected function accountTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->account_type) {
                'asset' => 'Asset Account',
                'liability' => 'Liability Account',
                default => 'Unknown',
            }
        );
    }

    /**
     * Scope a query to only include asset account entries.
     */
    public function scopeAssets($query)
    {
        return $query->where('account_type', 'asset');
    }

    /**
     * Scope a query to only include liability account entries.
     */
    public function scopeLiabilities($query)
    {
        return $query->where('account_type', 'liability');
    }

    /**
     * Scope a query to only include entries of a specific transaction type.
     */
    public function scopeTransactionType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    /**
     * Get the amount based on the transaction type.
     */
    public function getAmountAttribute()
    {
        return $this->debit > 0 ? $this->debit : $this->credit;
    }

    /**
     * Get the amount type (debit or credit).
     */
    public function getAmountTypeAttribute()
    {
        return $this->debit > 0 ? 'debit' : 'credit';
    }
}