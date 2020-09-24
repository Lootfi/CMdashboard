<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{

    protected $fillable = [
        "name",
        "slug",
        "website",
        "maison",
        "phone",
        "address",
        "indepndant",
        "description",
        "facebook",
        "instagram",
        "twitter",
        "sous_labels",
        "artists",
        "genres",
    ];

    protected $appends = [
        'LogoLink',
    ];

    public function getLogoLinkAttribute()
    {
        return '/images/labels/' . $this->logo;
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'entreprise_contacts', 'entreprise_id', 'contact_id');
    }

    public function entreprise_contacts()
    {
        return $this->hasMany(EntrepriseContact::class, 'entreprise_id', 'id');
    }
}
