<?php

namespace App\Http\Controllers\Api\Entreprises;

use App\Entreprise;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            return response()->json('Already exists');
        }
        Entreprise::create([
            'name' => request('name'),
            'slug' => str_slug(request('name', str_slug(now()))) . "-" . substr(md5(mt_rand()), 0, 6)
        ]);

        return response()->json('Entreprise Created', 200);
    }

    public function edit(Request $request, $slug)
    {
        if (!$entreprise = Entreprise::where('slug', $slug)->first()) {
            return response()->json('Not Found', 404);
        }
        if (request('name')) {
            $entreprise->name = request('name');
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
