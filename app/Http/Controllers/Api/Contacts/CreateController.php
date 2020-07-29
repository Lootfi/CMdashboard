<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contact;
use App\ContactSocialLinks;
use Carbon\Carbon;
use Hash;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Intervention\Image\Facades\Image;

class CreateController extends Controller
{
	public function create()
	{

		$payload = request(['name', 'email', 'mobile', 'country', 'state', 'adresse', 'picture']);

		if (!Contact::where('email', $payload['email'])->first()) {

			$contact = new Contact();
			$contact->name = $payload['name'];
			$contact->email = $payload['email'];
			$contact->slug = str_slug($payload['name']) . "-" . substr(md5(mt_rand()), 0, 6);
			$contact->created_at = now();
			$contact->updated_at = now();
			$contact->mobile = $payload['mobile'];
			if (request('country')) {
				$contact->country = $payload['country'];
			}
			if (request('adresse')) {
				$contact->adresse = $payload['adresse'];
			}
			if (request('state')) {
				$contact->state = $payload['state'];
			}
			if (request('picture')) {
				$imageData = request('picture');
				$fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];

				$PicturePath = public_path('images/contacts/') . $fileName;
				Image::make($imageData)->save($PicturePath);
				ImageOptimizer::optimize($PicturePath);

				$contact->picture = $fileName;
			}
			$contact->save();

			$social = new ContactSocialLinks(['contact_id' => $contact->id]);
			$social->save();

			return response()->json("Contact Created");
		} else {

			return response()->json("Contact exists");
		}
	}
}
