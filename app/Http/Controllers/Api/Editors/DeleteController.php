<?php

namespace App\Http\Controllers\Api\Editors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;
use File;

class DeleteController extends Controller
{
    public function delete($slug)
    {

        if ($administrator = Administrator::fetchBySlug($slug)) {
            $oldAvatar = public_path('images/admin/users/avatars/') . $administrator->picture;
            File::delete($oldAvatar);
            $administrator->delete();
            return response()->json('Administrator deleted');
        }

        return response()->json('Administrator not found ! ');
    }
}
