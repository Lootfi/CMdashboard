<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntrepriseContact extends Model
{
    protected $fillable = ['id_contact', 'id_entreprise'];
}
