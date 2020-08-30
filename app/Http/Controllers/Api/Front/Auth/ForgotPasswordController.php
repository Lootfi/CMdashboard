<?php

namespace App\Http\Controllers\Api\Front\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Models\Artist;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{


    use SendsPasswordResetEmails;

    public function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json(['message' => trans($response)]);
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
