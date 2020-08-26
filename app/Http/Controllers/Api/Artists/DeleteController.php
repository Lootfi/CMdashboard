<?php

namespace App\Http\Controllers\Api\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DeleteController extends Controller
{
    public function delete($slug)
    {

        if ($artist = Artist::fetchBySlug($slug)) {
            if ($artist->avatar != 'default.jpeg') {
                $oldAvatar = public_path('images/artists/') . $artist->avatar;
                File::delete($oldAvatar);
            }
            $artist->delete();
            return response()->json('artist deleted');
        }

        return response()->json('artist not found ! ');
    }
}
