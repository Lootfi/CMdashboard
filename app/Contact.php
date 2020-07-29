<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    // protected $appends = ['Picture'];

    public static function fetchBySlug($slug)
    {
        return self::with('social')->where('slug', $slug)->first();
    }

    // public function getPictureAttribute()
    // {
    //     return "/images/contacts/" . $model->picture;
    // }

    public function social()
    {
        return $this->hasOne(ContactSocialLinks::class);
    }

    public function type()
    {
        return $this->belongsTo(ContactType::class);
    }
}
