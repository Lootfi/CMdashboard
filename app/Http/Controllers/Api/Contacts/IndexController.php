<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{

	public function getAll()
	{
		$contacts = Contact::all();
		return $contacts;
	}
}
