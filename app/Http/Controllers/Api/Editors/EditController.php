<?php

namespace App\Http\Controllers\Api\Editors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;

class EditController extends Controller
{
    public function editUser($slug)
    {

        $payload = request(['full_name', 'username', 'email', 'password', 'role', 'status', 'mobile', 'country', 'adresse', 'gender', 'biography', 'facebook', 'twitter', 'instagram']);

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

            $details = $administrator->Details;

            if (request('mobile')) {
                $details->mobile = $payload['mobile'];
            }
            if (request('country')) {
                $details->country = $payload['country'];
            }
            if (request('adresse')) {
                $details->adresse = $payload['adresse'];
            }
            if (request('biography')) {
                $details->biography = $payload['biography'];
            }
            if (request('gender')) {
                $details->gender = $payload['gender'];
            }
            if (request('twitter')) {

                $details->twitter = $payload['twitter'];
            }
            if (request('facebook')) {

                $details->facebook = $payload['facebook'];
            }
            if (request('instagram')) {

                $details->instagram = $payload['instagram'];
            }

            $details->updated_at = now();
            $details->save();
            $administrator->updated_at = now();
            $administrator->save();
            return response()->json([

                'user' => $administrator,
            ]);
        }

        return response()->json("Administrator not found ! ");
    }
}
