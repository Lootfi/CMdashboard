<?php

namespace App\Http\Controllers\Api\Editors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;

class StatusController extends Controller
{
	public function activate($slug)
	{

		if ($administrator = Administrator::fetchBySlug($slug)) {

			if ($administrator->status != 1) {

				$administrator->status = 1;
				$administrator->updated_at = now();
				$administrator->save();

				return response()->json('Administrator activated');
			}

			return response()->json("Administrator already active");
		}


		return response()->json("Administrator not found ! ");
	}

	public function suspend($slug)
	{

		if ($administrator = Administrator::fetchBySlug($slug)) {

			if ($administrator->status != 0) {

				$administrator->status = 0;
				$administrator->updated_at = now();
				$administrator->save();

				return response()->json('Administrator suspended');
			}

			return response()->json("Administrator already suspended");
		}


		return response()->json("Administrator not found ! ");
	}
}
