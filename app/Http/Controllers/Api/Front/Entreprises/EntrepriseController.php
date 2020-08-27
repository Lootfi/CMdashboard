<?php

namespace App\Http\Controllers\Api\Front\Entreprises;

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
}
