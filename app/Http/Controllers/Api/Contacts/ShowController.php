<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowController extends Controller
{
	public function show($slug)
	{
		if ($contact = Contact::fetchBySlug($slug)) {

			return $contact;
		}

		return response()->json("Contact not found");
	}
}
