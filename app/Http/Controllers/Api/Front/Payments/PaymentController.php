<?php

namespace App\Http\Controllers\Api\Front\Payments;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

use PayPal\Api\Amount;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;
use PayPal\Api\Order;
use PayPal\Api\Payment;

class PaymentController extends Controller
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

    public function get_info()
    {
        try {
            $authorization = Authorization::get('7LS07389ED560333S', $this->_api_context);

            dd($authorization);
        } catch (\Exception $ex) {
            dd($ex);
            // dd($capture, $authorization);
        }
    }


    /*
    *   GETS PAYMENT INFO
    *   if payment is captured, returns payment info
    *   else it returns auth info
    *   all info returned with links, [refund, capture ..etc]
    */
    public function info()
    {
        if ($user = Artist::find(request('id'))) {
            $auth = $user->payment_auth;
            try {
                $authorization = Authorization::get($auth->auth_id, $this->_api_context);
                $amt = new Amount();
                $amt->setCurrency($authorization->getAmount()->getCurrency())->setTotal($authorization->getAmount()->getTotal());

                $capture = new Capture();
                $capture->setAmount($amt);

                $getCapture = $authorization->capture($capture, $this->_api_context);

                if ($getCapture->getState() == "completed") {
                    $user->payment_confirmed = true;
                    $user->payment_method = 'paypal_' . $getCapture->getId();
                    $user->updated_at = now();
                    $user->save();
                }
            } catch (\Exception $ex) {
                if ($authorization->getState() == "captured") {
                    $user->payment_confirmed = true;
                    $user->updated_at = now();
                    $user->save();
                }
            }
        }
    }
}
