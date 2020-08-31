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
        $faqs = Faq::all();
        return response()->json($faqs->where('frontpage', true)->sortBy('order'));
    }
}
