<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'id',
        "name",
        "active",
        "alias",
        "templateType"
    ];
}
