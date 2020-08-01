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

class CreateController extends Controller
{
	public function create()
	{

		if (!Artist::fetchByEmail(request('email'))) {

			$artist = new Artist();
			$artist->name = request('name');
			$artist->slug = str_slug(request('name', str_slug(now()))) . "-" . substr(md5(mt_rand()), 0, 6);
			$artist->password = Hash::make(request('password'));
			$artist->email = request('email', '');
			$artist->status = 1;
			$artist->payment_method = null;
			$artist->payment_confirmed = 0;
			$artist->provider = null;
			$artist->provider_id = null;
			$artist->created_at = now();
			$artist->updated_at = now();
			if (request('avatar')) {
				$imageData = request('avatar');
				$fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
				$AvatarPath = public_path('images/artists/') . $fileName;
				Image::make(request('avatar'))->save($AvatarPath);
				ImageOptimizer::optimize($AvatarPath);
				$artist->avatar = $fileName;
			}
			$artist->save();

			return $artist;
		}

		return "Artist Already exists";
	}
}
