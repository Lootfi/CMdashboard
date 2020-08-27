<?php

namespace App\Http\Controllers\Api\Front\Contacts;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::with('entreprises')->get();

        return response()->json($contacts);
    }

    public function show($slug)
    {
        $contact = Contact::where('slug', $slug)->with('entreprises')->first();

        return response()->json($contact);
    }
}
