<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $appends = ['Picture'];

    public static function fetchBySlug($slug)
    {

        return self::where('slug', $slug)->first();
    }

    public function getPictureAttribute()
    {
        return "/images/contacts/" . $this->picture;
    }
}
