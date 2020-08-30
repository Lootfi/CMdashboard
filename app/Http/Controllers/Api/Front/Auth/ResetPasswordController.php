<?php

namespace App\Http\Controllers\Api\Front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json(['response' => trans($response)]);
    }

    public function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json(['error' => trans($response)], 422);
    }

    public function broker()
    {
        return Password::broker('clients');
    }
}
