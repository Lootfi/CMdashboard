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
            if ($contact->picture != 'default.jpeg') {
                $oldPic = public_path('images/contacts/') . $contact->picture;
                File::delete($oldPic);
            }
            $contact->delete();
            return response()->json(['success' => 'Contact deleted', 'contacts' => Contact::all()]);
        }

        return response()->json('Contact not found ! ', 404);
    }
}
