<?php

namespace App\Http\Controllers\Api\Editors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;
use App\Models\AdministratorDetail;
use Carbon\Carbon;
use Hash;
use ImageOptimizer;

class CreateController extends Controller
{
	public function create()
	{

		$payload = request(['full_name', 'username', 'email', 'password', 'role', 'biography', 'picture', 'mobile', 'adresse', 'gender', 'country', 'status', 'avatar']);

		if (!Administrator::where('email', $payload['email'])->first()) {
			$administrator = Administrator::create(
				[
					'email' => $payload['email'],
					'full_name' => $payload['full_name'],
					'username' => $payload['username'],
					'password' => Hash::make($payload['password']),
					'slug' => str_slug($payload['full_name']) . "-" . substr(md5(mt_rand()), 0, 6),
					'role' => $payload['role']['value'],
				]
			);
			if (request('status')['label'] == "Activé") {
				$administrator->status = 1;
			}
			if (request('status')['label'] == "Suspendu") {
				$administrator->status = 2;
			}
			$administrator->created_at = now();
			$administrator->updated_at = now();
			$administrator->save();
			$details = new AdministratorDetail(['admin_id' => $administrator->id]);
			if (request('biography')) {
				$details->biography = $payload['biography'];
			}
			if (request('mobile')) {
				$details->mobile = $payload['mobile'];
			}
			if (request('country')) {
				$details->country = $payload['country'];
			}
			if (request('adresse')) {
				$details->adresse = $payload['adresse'];
			}
			if (request('gender')) {
				$details->gender = $payload['gender'];
			}
			if (request('avatar')) {
				$imageData = request('avatar');
				$fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
				$AvatarPath = public_path('images/admin/users/avatars/') . $fileName;
				\Image::make(request('avatar'))->save($AvatarPath);
				ImageOptimizer::optimize($AvatarPath);

				$details->picture = $fileName;
			}
			$details->created_at = now();
			$details->updated_at = now();
			$details->save();

			return response()->json("Administrator Created");
		} else {

			return response()->json("Administrator exists");
		}
	}
}
