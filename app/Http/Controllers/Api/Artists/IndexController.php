<?php

namespace App\Http\Controllers\Api\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function getAll()
    {
        return Artist::all();
    }

    public function potentials()
    {
        $artists =  Artist::where('payment_confirmed', false)->orderBy('created_at', 'DESC')->get();

        foreach ($artists as $key => $artist) {
            $artist->can_capture = Carbon::now()->subDay() > Carbon::createFromDate($artist->updated_at);
        }

        return $artists;
    }
}
