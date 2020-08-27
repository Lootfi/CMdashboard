<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\ClientPaymentAuthorization;

class Artist extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $attributes = [
        'name' => '',
        'avatar' => 'default.jpeg',
        'payment_method' => '',
        'payment_authorized' => false,
        'status' => false,
        'username' => ''
    ];

    protected $hidden = [
        'password', 'email_verified_at', 'remember_token'
    ];


    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'StatusName',
        'PaymentMethodName',
        'AvatarLink',
        'ConnectedVia'
    ];


    public $timestamps = false;

    public function payment_auth()
    {
        return $this->hasOne(ClientPaymentAuthorization::class, 'client_id', 'id');
    }


    public function getStatusNameAttribute()
    {

        if ($this->status == 1) {

            return ['label' => "Activé", 'value' => $this->status];
        }
        if ($this->status == 0) {

            return ['label' => "Suspendu", 'value' => $this->status];
        }
    }

    public function getConnectedViaAttribute()
    {
        return $this->provider ? ucfirst($this->provider) : 'Email';
    }


    public function getPaymentMethodNameAttribute()
    {
        if ($this->payment_confirmed == 0) return 'Pas encore';
        else if (preg_match('/.*(stripe).*/', $this->payment_method)) return 'Stripe';
        else return 'PayPal';
    }

    public function getAvatarLinkAttribute()
    {
        return filter_var($this->avatar, FILTER_VALIDATE_URL) ? $this->avatar : '/images/artists/' . $this->avatar;
    }

    public static function fetchBySlug($slug)
    {

        return self::where('slug', $slug)->first();
    }

    public static function fetchByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
