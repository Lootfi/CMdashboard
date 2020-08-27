<?php

namespace App\Http\Controllers\Api\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use Carbon\Carbon;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class EditController extends Controller
{
	public function edit($slug)
	{

		if ($artist = Artist::fetchBySlug($slug)) {
			$artist->name = request('name', $artist->name);
			if (request('password')) {
				$artist->password = Hash::make(request('password'));
			}
			if (request('email')) {
				$artist->email = request('email');
			}
			if (request('status')) {
				if (request('status')['label'] == "Activé") {
					$artist->status = 1;
				}
				if (request('status')['label'] == "Suspendu") {
					$artist->status = 0;
				}
			}
			if (request('payment_method')) {
				$artist->payment_method = request('payment_method');
			}
			if (request('payment_confirmed')) {
				$artist->payment_confirmed = request('payment_confirmed');
			}
			if (request('provider')) {
				$artist->provider = request('provider');
			}
			if (request('provider_id')) {
				$artist->provider_id = request('provider_id');
			}

			if (request('avatar')) {
				$imageData = request('avatar');
				$fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
				$AvatarPath = public_path('images/artists/') . $fileName;
				$oldPicture = public_path('images/artists/') . $artist->avatar;
				if ($artist->avatar != 'default.jpeg')
					File::delete($oldPicture);
				Image::make(request('avatar'))->save($AvatarPath);
				$artist->avatar = $fileName;
			}
			$artist->updated_at = now();
			$artist->save();

			return $artist;
		}

		return "Artist not found";
	}
}
