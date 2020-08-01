<?php

namespace App\Http\Controllers\Api\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function activate($slug)
    {

        if ($artist = Artist::fetchBySlug($slug)) {

            if ($artist->status != 1) {

                $artist->status = 1;
                $artist->updated_at = now();
                $artist->save();

                return response()->json('artist activated');
            }

            return response()->json("artist already active");
        }


        return response()->json("artist not found ! ");
    }

    public function suspend($slug)
    {

        if ($artist = Artist::fetchBySlug($slug)) {

            if ($artist->status != 0) {

                $artist->status = 0;
                $artist->updated_at = now();
                $artist->save();

                return response()->json('artist suspended');
            }

            return response()->json("artist already suspended");
        }


        return response()->json("artist not found ! ");
    }
}
