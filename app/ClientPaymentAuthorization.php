<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Artist;

class ClientPaymentAuthorization extends Model
{
    protected $fillable = ['client_id', 'order_id', 'auth_id', 'stripe_customer_id', 'capture_id'];

    protected $attributes = [
        "order_id" => '',
        "auth_id" => '',
        "capture_id" => '',
        'stripe_customer_id' => ''
    ];
    public function client()
    {
        return $this->belongsTo(Artist::class, 'client_id', 'id');
    }
}
