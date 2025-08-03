<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ShareholderTransaction extends Model
{
    use HasFactory;

    const ALL_TYPES = ['Investment', 'Commission', 'Dividend', 'Withdrawal', 'Salary', 'Expense'];

   
    const CREDIT_TYPES = ['Investment', 'Commission', 'Dividend'];

    protected $fillable = [
        'shareholder_id', 'transaction_date', 'type', 'amount', 'description'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function shareholder()
    {
        return $this->belongsTo(Shareholder::class);
    }

    protected function isCredit(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->type, self::CREDIT_TYPES)
        );
    }


    protected function isDebit(): Attribute
    {
        return Attribute::make(
            get: fn () => ! in_array($this->type, self::CREDIT_TYPES)
        );
    }
}