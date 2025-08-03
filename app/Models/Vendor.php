<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'vendor_name',
        'phone',
        'email',
        'address',
        'concern_person',
        'designation',
    ];

    public function ledgers()
    {
        return $this->hasMany(VendorLedger::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get the available advance balance for this vendor.
     * This is money the vendor paid us that hasn't been used.
     */
   public function getAvailableAdvanceAttribute()
{
    // Calculate total debits (increases advance) for asset account
    $totalDebits = $this->ledgers()
        ->where('account_type', 'asset')
        ->sum('debit');
    
    // Calculate total credits (decreases advance) for asset account
    $totalCredits = $this->ledgers()
        ->where('account_type', 'asset')
        ->sum('credit');
        
    return $totalDebits - $totalCredits;
}

    /**
     * Get the current payable balance for this vendor.
     * This is money we owe the vendor for purchases.
     */
    public function getPayableBalanceAttribute()
{
    // Calculate total credits (increases payable) for liability account
    $totalCredits = $this->ledgers()
        ->where('account_type', 'liability')
        ->sum('credit');
    
    // Calculate total debits (decreases payable) for liability account
    $totalDebits = $this->ledgers()
        ->where('account_type', 'liability')
        ->sum('debit');
        
    return $totalCredits - $totalDebits;
}

    /**
     * Get the current net position.
     * Positive means you owe the vendor, negative means vendor owes you.
     */
    public function getNetPositionAttribute()
    {
        return $this->payable_balance - $this->available_advance;
    }
}