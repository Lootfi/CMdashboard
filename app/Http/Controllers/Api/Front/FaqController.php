<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Faq;

class FaqController extends Controller
{
    public function index()
    {

        return response()->json(Faq::all());
    }
}
