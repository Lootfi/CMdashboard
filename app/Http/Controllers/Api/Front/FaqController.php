<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Faq;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('faqs')->orderBy('order')->get());
    }

    public function front()
    {
        return response()->json(DB::table('faqs')->where('frontpage', 1)->orderBy('order')->get());
    }
}
