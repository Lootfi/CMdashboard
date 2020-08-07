<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $attributes = [
        'name' => '',
        'avatar' => '',
        'payment_method' => '',
        'status' => false
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
    protected $appends = ['StatusName', 'PaymentMethodName', 'AvatarLink', 'ConnectedVia'];


    public $timestamps = false;


    public function getStatusNameAttribute()
    {

        if ($this->status == 1) {

            return "Activé";
        }
        if ($this->status == 2) {

            return "suspended";
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
}
