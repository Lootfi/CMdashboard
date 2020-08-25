<?php

namespace App\Http\Controllers\Api\Faqs;

use App\Faq;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return response()->json(Faq::all());
    }

    public function show($id)
    {
        return response()->json(Faq::where('id', $id)->first());
    }

    public function create()
    {
        Faq::create([
            'question' => request('question'),
            'answer' => request('answer')
        ]);
    }

    public function edit($id)
    {
        $faq = Faq::where('id', $id)->first();

        $faq->question = request('question');
        $faq->answer = request('answer');

        $faq->save();
    }

    public function delete($id)
    {
        $faq = Faq::where('id', $id)->first();

        $faq->delete();
    }
}
