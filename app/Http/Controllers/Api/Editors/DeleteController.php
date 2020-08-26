<?php

namespace App\Http\Controllers\Api\Editors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;
use Illuminate\Support\Facades\File;

class DeleteController extends Controller
{
    public function delete($slug)
    {

        if ($administrator = Administrator::fetchBySlug($slug)) {
            $oldAvatar = public_path('images/admin/users/avatars/') . $administrator->picture;
            if ($administrator->picture != "default.jpeg")
                File::delete($oldAvatar);
            $administrator->delete();
            return response()->json(['success' => 'Administrator deleted']);
        }

        return response()->json('Administrator not found ! ', 404);
    }
}
