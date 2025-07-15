<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{

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

    public function getAvailableAdvanceAttribute()
    {
        if (!$this->relationLoaded('ledgers')) {
            $this->load('ledgers');
        }

        // Total advance payments received (these are CREDITS in your system)
        $totalAdvances = $this->ledgers
            ->where('transaction_type', 'advance_payment')
            ->sum('credit');

        // Total advances already used/adjusted (these are also CREDITS)
        $totalAdjusted = $this->ledgers()
            ->where('transaction_type', 'advance_adjustment')
            ->sum('credit');

        // The available amount is what was received minus what has been used.
        // The (float) cast ensures we always return a number (0.0 if null).
        return (float) ($totalAdvances - $totalAdjusted);
    }
}


