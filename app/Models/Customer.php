<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{

    protected $fillable = [
        'name', 'phone', 'email', 'address', 'concern', 'designation',
        'opening_balance', 'balance_type', 'notes', 'is_active',
        'created_by', 'updated_by'
    ];
}
