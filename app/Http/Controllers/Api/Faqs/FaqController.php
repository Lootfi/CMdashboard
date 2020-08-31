<?php

namespace App\Http\Controllers\Api\Faqs;

use App\Faq;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('faqs')->orderBy('order')->get());
    }

    public function changeOrder()
    {
        if (request('faqs')) {
            foreach (request('faqs') as $key => $question) {
                $faq = Faq::where('id', $question['id'])->first();
                $faq->order = $key;
                $faq->save();
            }
            return response()->json('success', 200);
        }
    }

    public function show($id)
    {
        return response()->json(Faq::where('id', $id)->first());
    }

    public function create()
    {
        $order = DB::table('faqs')->count();
        Faq::create([
            'question' => request('question'),
            'answer' => request('answer'),
            'order' => $order,
            'frontpage' => request('frontpage')
        ]);
    }

    public function edit($id)
    {
        $faq = Faq::where('id', $id)->first();

        $faq->question = request('question');
        $faq->answer = request('answer');
        $faq->frontpage = request('frontpage');

        $faq->save();
    }

    public function delete($id)
    {
        $faq = Faq::where('id', $id)->first();

        $faq->delete();
    }

    public function deleteSelected()
    {
        if (request('faqs')) {
            foreach (request('faqs') as $question) {
                Faq::where('id', $question['id'])->first()->delete();
            }
            return response()->json('success', 200);
        } else {
            return response()->json('no faqs selected', 404);
        }
    }
}
