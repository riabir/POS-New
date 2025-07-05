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
        'concern_person'
    ];
}
