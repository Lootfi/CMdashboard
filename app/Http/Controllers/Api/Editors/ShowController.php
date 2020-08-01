<?php

namespace App\Http\Controllers\Api\Editors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;

class ShowController extends Controller
{
	public function show($slug)
	{

		if ($admin = Administrator::fetchBySlug($slug)) {

			return $admin;
		}

		return response()->json("User not found", 404);
	}
}
