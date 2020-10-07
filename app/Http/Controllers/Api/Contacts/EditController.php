<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Contact;
use App\ContactSocialLinks;
use App\Entreprise;
use App\EntrepriseContact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;


class EditController extends Controller
{
    public function edit($slug)
    {
        $payload = request([
            'username', 'prenom', 'name', 'email', 'mobile', 'country', 'state', 'title',
            'picture',
            'instagram',
            'twitter',
            'linkedin',
            'type_id',
            'description_poste',
            'description_media'
        ]);

        if ($contact = Contact::fetchBySlug($slug)) {
            if (request('username')) {
                $contact->name = $payload['username'];
            }
            if (request('prenom')) {
                $contact->name = $payload['prenom'];
            }
            if (request('name')) {
                $contact->name = $payload['name'];
            }
            if (request('email')) {
                $contact->email = $payload['email'];
            }
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

            if (request('type_id')) {
                $contact->type_id = $payload['type_id'];
            }

            if (request('description_poste')) {
                $contact->description_poste = $payload['description_poste'];
            }

            if (request('description_media')) {
                $contact->description_media = $payload['description_media'];
            }

            if (request('picture')) {
                $contact->picture = $this->uploadPic(request()->get('picture'), $slug);
            }

            EntrepriseContact::where('contact_id', $contact->id)->delete();
            if (request('entreprises')) {

                foreach (request('entreprises') as $var) {
                    $item = collect($var);
                    if ($item->has('value')) { //entreprise exists in database
                        $entreprise = Entreprise::where('slug', $item['value'])->first();
                        EntrepriseContact::firstOrCreate([
                            'contact_id' => $contact->id,
                            'entreprise_id' => $entreprise->id
                        ]);
                    } else { // entreprise doesn't exist in database
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


            $social_links = $contact->social;

            if (request('twitter')) {
                $social_links->twitter = $payload['twitter'];
            }
            if (request('linkedin')) {
                $social_links->linkedin = $payload['linkedin'];
            }
            if (request('instagram')) {
                $social_links->instagram = $payload['instagram'];
            }


            $contact->updated_at = now();
            $contact->save();
            $social_links->save();
            return response()->json([
                'contact' => $contact,
            ]);
        }

        return response()->json("Contact not found ! ");
    }

    public function uploadPic($imageData, $slug)
    {
        $contact = Contact::where('slug', $slug)->first();
        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
        $PicturePath = public_path('images/contacts/') . $fileName;
        $oldPicture = public_path('images/contacts/') . $contact->picture;

        if ($contact->picture != 'default.jpeg')
            File::delete($oldPicture);
        Image::make($imageData)->save($PicturePath);
        ImageOptimizer::optimize($PicturePath);

        return $fileName;
    }
}
