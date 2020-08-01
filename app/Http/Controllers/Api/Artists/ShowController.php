<?php

namespace App\Http\Controllers\Api\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;

class ShowController extends Controller
{

	public function show($slug)
	{
		if ($artist = Artist::fetchBySlug($slug)) {
			return $artist;
		}
		return 'Artist not found';
	}
}
