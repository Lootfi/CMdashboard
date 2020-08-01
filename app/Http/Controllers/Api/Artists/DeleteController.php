<?php

namespace App\Http\Controllers\Api\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function delete($slug)
    {

        if ($artist = Artist::fetchBySlug($slug)) {
            $oldAvatar = public_path('images/artists/') . $artist->avatar;
            File::delete($oldAvatar);
            $artist->delete();
            return response()->json('artist deleted');
        }

        return response()->json('artist not found ! ');
    }
}
