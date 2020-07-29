<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;


class PictureController extends Controller
{
	public function upload(Request $request, $slug)
	{

		if ($contact = Contact::fetchBySlug($slug)) {
			if ($request->get('picture')) {
				$imageData = $request->get('picture');

				$fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
				$PicturePath = public_path('images/contacts/') . $fileName;
				$oldPicture = public_path('images/contacts/') . $contact->picture;

				File::delete($oldPicture);
				Image::make($request->get('avatar'))->save($PicturePath);
				ImageOptimizer::optimize($PicturePath);

				$contact->picture = $fileName;
				$contact->updated_at = now();
				$contact->save();

				return response()->json([
					'contact' => $contact,
				]);
			}
		}
		return "Contact does not exist";
	}
}
