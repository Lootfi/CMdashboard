<?php

namespace App\Http\Controllers\Api\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use Carbon\Carbon;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class EditController extends Controller
{
	public function edit($slug)
	{

		if ($artist = Artist::fetchBySlug($slug)) {

			$artist->name = request('name');
			$artist->password = Hash::make(request('password'));
			$artist->email = request('email', '');
			$artist->status = request('status');
			$artist->payment_method = request('payment_method');
			$artist->payment_confirmed = request('payment_confirmed');
			$artist->provider = request('provider', null);
			$artist->provider_id = request('provider_id', null);

			if (request('avatar')) {
				$imageData = request('avatar');
				$fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
				$AvatarPath = public_path('images/artists/') . $fileName;
				$oldPicture = public_path('images/artists/') . Artist::where('slug', $slug)->first()->avatar;

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
