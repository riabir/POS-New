<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Vendor extends Model 
{

   protected $fillable = [
        'vendor_name',
        'phone',
        'email',
        'address',
        'concern_person'
    ];
  /**
     * Defines the relationship: A Vendor has many VendorLedger entries.
     * This method should only exist ONCE in this file.
     */
    public function ledgers()
    {
        return $this->hasMany(VendorLedger::class);
    }

    /**
     * Defines the relationship: A Vendor can have many Purchase records.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    
    /**
     * Defines the relationship: A Vendor can have many VendorAccount records.
     */
    public function accounts()
    {
        return $this->hasMany(VendorAccount::class);
    }

    /**
     * Calculates the available advance balance for the vendor.
     * This accessor relies on the ledgers() relationship.
     */
    public function getAvailableAdvanceAttribute(): float
    {
        // 1. Sum all money given as an initial advance.
        $totalAdvancePaid = $this->ledgers()
            ->where('description', 'Advance Payment')
            ->sum('debit');

        // 2. Sum all advance money that was later applied to bills.
        $totalAdvanceUsed = $this->ledgers()
            ->where('description', 'like', 'Payment via advance for%')
            ->sum('debit');

        $available = $totalAdvancePaid - $totalAdvanceUsed;

        // Return 0 if the result is negative or zero.
        return $available > 0 ? $available : 0;
    }
}