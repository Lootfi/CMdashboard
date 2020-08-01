<?php

namespace App\Http\Controllers\Api\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;


class IndexController extends Controller
{
    public function getAll()
    {
        return Artist::all();
    }
}
