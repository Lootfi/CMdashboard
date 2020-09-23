<?php

namespace App\Http\Controllers\Api\Entreprises;

use App\Entreprise;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Psy\Util\Json;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Intervention\Image\Facades\Image;

class EntrepriseController extends Controller
{
    public function index()
    {
        $entreprises = Entreprise::withCount('contacts')->get();

        return response()->json($entreprises);
    }

    public function show($slug)
    {
        if (!$entreprise = Entreprise::where('slug', $slug)->first()) {
            return response()->json('Not Found', 404);
        }

        return response()->json($entreprise);
    }

    public function create()
    {
        if ($entreprise = Entreprise::where('name', request('name'))->first()) {
            return response()->json('Un Label de meme nom déja existe', 500);
        }

        $entreprise = Entreprise::create([
            'name' => request('name'),
            'slug' => str_slug(request('name', str_slug(now()))) . "-" . substr(md5(mt_rand()), 0, 6),
            "website" => request('website'),
            "maison" => request('maison'),
            "phone" => request('phone'),
            "address" => request('address'),
            "indepndant" => request('indepndant'),
            "description" => request('description'),
            "facebook" => request('facebook'),
            "instagram" => request('instagram'),
            "twitter" => request('twitter'),
            "sous_labels" => json_encode(request('sous_labels')),
            "artists" => json_encode(request('artists')),
            "genres" => json_encode(request('genres')),
        ]);


        if (request('avatar')) {
            $imageData = request('avatar');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            $AvatarPath = public_path('images/labels/') . $fileName;
            Image::make(request('avatar'))->save($AvatarPath);
            ImageOptimizer::optimize($AvatarPath);
            $entreprise->logo = $fileName;
        } else {
            $entreprise->logo = "default.jpeg";
        }

        $entreprise->save();
        return response()->json('Entreprise Created', 200);
    }

    public function edit(Request $request, $slug)
    {
        if (!$entreprise = Entreprise::where('slug', $slug)->first()) {
            return response()->json('Not Found', 404);
        }
        $entreprise->update([
            'name' => request('name'),
            "website" => request('website'),
            "maison" => request('maison'),
            "phone" => request('phone'),
            "address" => request('address'),
            "indepndant" => request('indepndant'),
            "description" => request('description'),
            "facebook" => request('facebook'),
            "instagram" => request('instagram'),
            "twitter" => request('twitter'),
            "sous_labels" => json_encode(request('sous_labels')),
            "artists" => json_encode(request('artists')),
            "genres" => json_encode(request('genres')),
        ]);

        if ($request->get('avatar')) {
            $imageData = $request->get('avatar');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            $AvatarPath = public_path('images/labels/') . $fileName;
            $oldAvatar = public_path('images/labels/') . $entreprise->logo;
            if ($entreprise->logo != "default.jpeg")
                File::delete($oldAvatar);
            Image::make($request->get('avatar'))->save($AvatarPath);
            ImageOptimizer::optimize($AvatarPath);
            $entreprise->logo = $fileName;
        }

        $entreprise->updated_at = now();
        $entreprise->save();

        return response()->json('Entreprise Edited', 200);
    }

    public function delete($slug)
    {
        if (!$entreprise = Entreprise::where('slug', $slug)->first()) {
            return response()->json('Not Found', 404);
        }
        $entreprise->delete();

        return response()->json('Entreprise Deleted', 200);
    }
}
