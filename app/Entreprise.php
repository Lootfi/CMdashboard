<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{


    protected $fillable = [
        'name', 'slug'
    ];
    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'entreprise_contacts', 'entreprise_id', 'contact_id');
    }
}
