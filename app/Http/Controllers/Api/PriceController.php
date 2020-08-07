<?php

namespace App\Http\Controllers\Api;

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

    public function change()
    {
        $price = Price::first();

        $price->amount = request('price');
        $price->save();

        return response()->json($price->amount);
    }
}
