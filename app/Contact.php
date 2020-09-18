<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $attributes = [
        'mobile' => '',
        'title' => '',
        'prenom' => '',
        'username' => '',
        'type_id' => 0
    ];

    // protected $appends = ['Picture'];

    // public function getPictureAttribute()
    // {
    //     return "/images/contacts/" . $model->picture;
    // }

    public static function fetchBySlug($slug)
    {
        return self::with('social')->where('slug', $slug)->first();
    }

    public function social()
    {
        return $this->hasOne(ContactSocialLinks::class);
    }

    public function type()
    {
        return $this->belongsTo(ContactType::class, 'type_id', 'id');
    }

    public function entreprises()
    {
        return $this->belongsToMany(Entreprise::class, 'entreprise_contacts', 'contact_id', 'entreprise_id');
    }
}
