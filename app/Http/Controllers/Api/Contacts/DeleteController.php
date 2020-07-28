<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;

class DeleteController extends Controller
{
    public function delete($slug)
    {

        if ($contact = Contact::fetchBySlug($slug)) {
            $oldPic = public_path('images/contacts/') . $contact->picture;
            File::delete($oldPic);
            $contact->delete();
            return response()->json('Contact deleted');
        }

        return response()->json('Contact not found ! ');
    }
}
