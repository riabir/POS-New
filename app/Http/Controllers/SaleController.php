<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaleController extends Controller
{
     public function create()
    {
        return view('sales.create');
    }
}
