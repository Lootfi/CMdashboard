<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function edit($slug)
    {
        $payload = request(['name', 'email', 'mobile', 'country', 'state', 'adresse', 'picture', 'facebook', 'twitter', 'instagram']);

        if ($contact = Contact::fetchBySlug($slug)) {
            if (request('name')) {
                $contact->name = $payload['name'];
            }
            if (request('email')) {
                $contact->email = $payload['email'];
            }
            if (request('mobile')) {
                $contact->mobile = $payload['mobile'];
            }
            if (request('country')) {
                $contact->country = $payload['country'];
            }
            if (request('state')) {
                $contact->state = $payload['state'];
            }
            if (request('adresse')) {
                $contact->adresse = $payload['adresse'];
            }
            if (request('twitter')) {
                $contact->twitter = $payload['twitter'];
            }
            if (request('facebook')) {
                $contact->facebook = $payload['facebook'];
            }
            if (request('instagram')) {
                $contact->instagram = $payload['instagram'];
            }
            $contact->updated_at = now();
            $contact->save();
            return response()->json([
                'contact' => $contact,
            ]);
        }

        return response()->json("Contact not found ! ");
    }
}
