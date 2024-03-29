<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class LoginController extends Controller
{
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user' => Auth::guard('api')->user(),
        ]);
    }

    // public function checkAuth()
    // {

    //     if (JWTAuth::parseToken()->authenticate()) {

    //         return response()->json(['authentificated' => true], 200);
    //     }
    //     return response()->json(['NonToken' => true], 401);
    // }

    public function checkAdmin()
    {
        return response()->json(true, 200);
    }

    public function checkEditor()
    {
        return response()->json(true, 200);
    }

    public function checkCommercial()
    {
        return response()->json(true, 200);
    }
}
