<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'customer_name',
        'phone',
        'email',
        'address',
        'concern',
        'designation',
        'opening_balance',
        'balance_type',
        'notes',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function ledgers()
    {
        return $this->hasMany(CustomerLedger::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Calculate available advance balance
    public function getAvailableAdvanceAttribute()
    {
        // Calculate the net balance in the asset account
        $assetBalance = $this->ledgers()
            ->where('account_type', 'asset')
            ->selectRaw('SUM(credit) - SUM(debit) as balance')
            ->value('balance') ?? 0;
            
        return $assetBalance;
    }

    // Calculate payable balance (amount customer owes)
    public function getPayableBalanceAttribute()
    {
        // Calculate the net balance in the liability account
        $liabilityBalance = $this->ledgers()
            ->where('account_type', 'liability')
            ->selectRaw('SUM(debit) - SUM(credit) as balance')
            ->value('balance') ?? 0;
            
        return $liabilityBalance;
    }

    // Calculate net position
    public function getNetPositionAttribute()
    {
        return $this->payable_balance - $this->available_advance;
    }
}