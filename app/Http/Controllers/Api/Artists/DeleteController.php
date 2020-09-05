<?php

namespace App\Http\Controllers\Api\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Authorization;

class DeleteController extends Controller
{
    protected $_api_context;

    public function __construct()
    {
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret']
        ));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function delete(Request $request, $slug)
    {

        if ($artist = Artist::fetchBySlug($slug)) {
            if ($artist->avatar != 'default.jpeg') {
                $oldAvatar = public_path('images/artists/') . $artist->avatar;
                File::delete($oldAvatar);
            }

            $auth = $artist->payment_auth;
            try {
                $authorization = Authorization::get($auth->auth_id, $this->_api_context);
                $voidedAuth = $authorization->void($this->_api_context);
            } catch (\Throwable $th) {
            }

            $auth->delete();
            $artist->delete();
            if (request('return_prospects')) {
                return response()->json(Artist::where('payment_confirmed', false)->get());
            }
            return response()->json('artist deleted');
        }

        return response()->json('artist not found ! ', 404);
    }
}
