<?php

namespace App\Http\Controllers\Api\Editors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;

class IndexController extends Controller
{

	public function getAll()
	{

		$users = Administrator::all();
		return $users;
	}
}
