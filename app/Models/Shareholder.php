<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class Shareholder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'is_active',
        'join_date',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'join_date' => 'date',
    ];


    public function transactions()
    {
        return $this->hasMany(ShareholderTransaction::class)->orderBy('transaction_date', 'desc');
    }

    protected function currentBalance(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->transactions()->sum(
                 DB::raw("CASE WHEN type IN ('Investment', 'Commission') THEN amount ELSE -amount END")
            )
        );
    }

    public function commissionsReceived()
    {
        return $this->morphMany(SaleCommission::class, 'recipient');
    }
}
