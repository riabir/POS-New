<?php

namespace App\Models;

// --- ADDED: Import the Attribute class for accessors ---
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'recipient_id',
        'recipient_type',
        'amount',
        'notes',
    ];

    /**
     * Get the parent sale model.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the owner of the commission (can be a Customer, Shareholder, etc.).
     */
    public function recipient()
    {
        return $this->morphTo();
    }

    /**
     * --- ADDED: Accessor for the recipient's name ---
     *
     * Get the recipient's name consistently, regardless of the model type.
     * This creates a virtual attribute that you can access like this: $commission->recipient_name
     */
    protected function recipientName(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Eager load the relationship if it hasn't been loaded yet.
                if (!$this->relationLoaded('recipient')) {
                    $this->load('recipient');
                }

                // Handle cases where the recipient might have been deleted.
                if (!$this->recipient) {
                    return 'Recipient Deleted';
                }

                // Check the recipient's type and return the correct name attribute.
                // This is the core of the polymorphic logic.
                if ($this->recipient_type === 'App\Models\Customer') {
                    return $this->recipient->customer_name;
                }
                
                if ($this->recipient_type === 'App\Models\Shareholder') {
                    return $this->recipient->name;
                }

                return 'Unknown Recipient Type';
            }
        );
    }
}