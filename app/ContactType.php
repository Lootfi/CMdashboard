<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    protected $fillable = ['name'];


    public function contacts()
    {
        return $this->hasMany(Contact::class, 'type_id', 'id');
    }
}
