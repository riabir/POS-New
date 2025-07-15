<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
   public function ledgers()
    {
        return $this->hasMany(VendorLedger::class);
    }

    public function accounts()
    {
        return $this->hasMany(VendorAccount::class);
    }

    /**
     * Calculates the available advance balance based on the overall ledger balance.
     * This is the correct, robust way to calculate it.
     */
  public function getAvailableAdvanceAttribute(): float
{
    // 1. First, it calculates the true balance. This number CAN be negative
    //    if the vendor is owed money.
    //    Example: $balance = 1000 (debit) - 5000 (credit) = -4000
    $balance = $this->ledgers()->sum('debit') - $this->ledgers()->sum('credit');

    // 2. THIS IS THE CRUCIAL PART. It uses a ternary operator as a guard.
    //    It checks: "Is the balance greater than 0?"
    //    - If YES, return the positive balance.
    //    - If NO (meaning the balance is 0 or negative), return 0.
    return $balance > 0 ? round($balance, 2) : 0;
}
}