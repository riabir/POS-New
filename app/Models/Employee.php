<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'emp_id',
    ];

    protected static function booted()
    {
        static::creating(function ($employee) {
            $latest = self::orderBy('id', 'desc')->first();
            $nextNumber = $latest ? (int) $latest->emp_id + 1 : 1;
            $employee->emp_id = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}
