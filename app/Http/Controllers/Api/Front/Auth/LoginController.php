<?php

namespace App\Http\Controllers\Api\Front\Auth;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$client = Artist::where('email', $credentials['email'])->first()) {
            return response()->json(['error' => "« " . $credentials['email'] . " » n’est associé à aucun compte ContactMajor."]);
        } else {
            if (Hash::check($credentials['password'], $client->password)) {
                if (!$token = Auth::guard('clients')->attempt($credentials)) {

                    return response()->json(['error' => 'Unauthorized'], 401);
                } elseif ($client->status == false) {
                    return response()->json(['error' => 'suspended'], 401);
                }

                return $this->respondWithToken($token);
            } else {
                return response()->json(['error' => "Le mot de passe entré est incorrect."]);
            }
        }
    }

    public function logout()
    {
        Auth::guard('clients')->logout();
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
            'expires_in'   => Auth::guard('clients')->factory()->getTTL() * 60,
            'user' => Auth::guard('clients')->user(),
        ]);
    }

    public function check()
    {
        return response()->json(true);
    }
}
