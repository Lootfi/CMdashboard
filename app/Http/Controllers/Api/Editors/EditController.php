<?php

namespace App\Http\Controllers\Api\Editors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;

class EditController extends Controller
{
    public function edit($slug)
    {

        $payload = request(['full_name', 'username', 'email', 'password', 'role', 'status']);

        if ($administrator = Administrator::fetchBySlug($slug)) {
            if (request('full_name')) {
                $administrator->full_name = $payload['full_name'];
            }
            if (request('username')) {
                $administrator->username = $payload['username'];
            }
            if (request('email')) {
                $administrator->email = $payload['email'];
            }
            if (request('password')) {
                $administrator->password = Hash::make($payload['password']);
            }
            if (request('status')) {
                if (request('status')['label'] == "Activé") {
                    $administrator->status = 1;
                }
                if (request('status')['label'] == "Suspendu") {
                    $administrator->status = 2;
                }
            }

            $administrator->updated_at = now();
            $administrator->save();
            return response()->json([
                'user' => $administrator,
            ]);
        }

        return response()->json("Administrator not found ! ");
    }
}
