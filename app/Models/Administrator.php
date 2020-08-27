<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrator extends Authenticatable implements JWTSubject
{
    use Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

    protected $fillable = ['full_name', 'username', 'email', 'role', 'slug', 'status', 'password', 'picture'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['StatusName', 'Avatar', 'RoleName'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'status'];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function getStatusNameAttribute()
    {

        if ($this->status == 1) {

            return "Activé";
        }

        if ($this->status == 2) {

            return "Suspendu";
        }
    }

    public function getRoleNameAttribute()
    {

        if ($this->role == "Admin") {
            return "Administrateur";
        } else if ($this->role == "Commercial") {
            return "Commercial";
        }
        return "Editeur";
    }

    public function getAvatarAttribute()
    {
        return "/images/admin/users/avatars/" . $this->picture;
    }
    public static function fetchBySlug($slug)
    {

        return self::where('slug', $slug)->first();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}
