<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactSocialLinks extends Model
{
    protected $fillable = ['contact_id', 'twitter', 'linkedin', 'instagram'];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
