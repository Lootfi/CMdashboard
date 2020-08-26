<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contact;
use App\ContactSocialLinks;
use App\Entreprise;
use App\EntrepriseContact;
use Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Intervention\Image\Facades\Image;

class CreateController extends Controller
{
	public function create()
	{

		$payload = request([
			'name', 'email', 'mobile', 'country', 'state', 'picture', 'title', 'prenom', 'username', 'entreprises'
		]);


		if (!Contact::where('email', $payload['email'])->first()) {

			try {
				DB::beginTransaction();

				$contact = new Contact();
				if (request('name')) {
					$contact->name = $payload['name'];
				}
				if (request('email')) {
					$contact->email = $payload['email'];
				}
				$contact->slug = $payload['name'] ? str_slug($payload['name']) : str_slug(explode('@', $payload['email'])[0]) . "-" . substr(md5(mt_rand()), 0, 6);

				if (request('mobile')) {
					$contact->mobile = $payload['mobile'];
				}
				if (request('country')) {
					$contact->country = $payload['country']['label'];
				}
				if (request('state')) {
					$contact->state = $payload['state'];
				}
				if (request('title')) {
					$contact->title = $payload['title'];
				}
				if (request('prenom')) {
					$contact->prenom = $payload['prenom'];
				}
				if (request('username')) {
					$contact->username = $payload['username'];
				}


				if (request('picture')) {
					$imageData = request('picture');
					$fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];

					$PicturePath = public_path('images/contacts/') . $fileName;
					Image::make($imageData)->save($PicturePath);
					ImageOptimizer::optimize($PicturePath);

					$contact->picture = $fileName;
				} else {
					$contact->picture = 'default.jpeg';
				}

				$contact->created_at = now();
				$contact->save();


				if (request('entreprises')) {

					foreach (request('entreprises') as $var) {
						$item = collect($var);
						if ($item->has('value')) {
							$entreprise = Entreprise::where('slug', $item['value'])->first();
							EntrepriseContact::create([
								'contact_id' => $contact->id,
								'entreprise_id' => $entreprise->id
							]);
						} else {
							$entreprise = Entreprise::create([
								'name' => ucfirst($item['label']),
								'slug' => str_slug($item['label']) . "-" . substr(md5(mt_rand()), 0, 6)
							]);
							EntrepriseContact::create([
								'contact_id' => $contact->id,
								'entreprise_id' => $entreprise->id
							]);
						}
					}
				}

				$social = new ContactSocialLinks(['contact_id' => $contact->id]);
				$social->save();

				DB::commit();

				return response()->json("Contact Created");
			} catch (\Throwable $th) {
				DB::rollback();
				return response()->json(['error' => $th], 500);
			}
		} else {

			return response()->json("Contact exists", 500);
		}
	}
}
