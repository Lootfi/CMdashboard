<?php

namespace App\Http\Controllers\Api\Front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{

    use VerifiesEmails;
}
