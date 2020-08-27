<?php

namespace App\Http\Controllers\Api\Front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user('clients');

        //TODO
    }

    public function uploadPicture(Request $request)
    {
        $user = $request->user('clients');

        //TODO
    }
}
