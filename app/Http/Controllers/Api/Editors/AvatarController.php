<?php

namespace App\Http\Controllers\Api\Editors;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class AvatarController extends Controller
{
	public function uploadAvatar(Request $request, $slug)
	{

		if ($administrator = Administrator::fetchBySlug($slug)) {
			if ($request->get('avatar')) {
				$imageData = $request->get('avatar');
				$fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
				$AvatarPath = public_path('images/admin/users/avatars/') . $fileName;
				$oldAvatar = public_path('images/admin/users/avatars/') . $administrator->picture;
				File::delete($oldAvatar);
				Image::make($request->get('avatar'))->save($AvatarPath);
				ImageOptimizer::optimize($AvatarPath);
				$administrator->picture = $fileName;
				$administrator->updated_at = now();
				$administrator->save();

				return response()->json([

					'user' => $administrator,
				]);
			}
		}
		return "User does not exist";
	}
}
