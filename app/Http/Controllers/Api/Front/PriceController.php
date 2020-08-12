<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function show()
    {
        $price = Price::first();

        return response()->json($price->amount);
    }
}
