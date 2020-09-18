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

    public function capture()
    {
        try {
            $user = Artist::where('id', 30)->with('payment_auth')->first();
            $authorization = Authorization::get('4UA01537HM049522A', $this->_api_context);

            dd($authorization);

            // $amt = new Amount();
            // $amt->setCurrency($authorization->getAmount()->getCurrency())->setTotal($authorization->getAmount()->getTotal());

            // $capture = new Capture();
            // $capture->setAmount($amt);

            // $getCapture = $authorization->capture($capture, $this->_api_context);

            // dd($getCapture);
        } catch (\Exception $ex) {
            // dd($capture, $authorization);
        }
    }


    /*
    *   GETS PAYMENT INFO
    *   if payment is captured, returns payment info
    *   else it returns auth info
    *   all info returned with links, [refund, capture ..etc]
    */
    public function info(Request $request)
    {
        $user = Artist::find(31);
        // $user = $request->user('clients');
        $auth = $user->payment_auth;

        if ($user->payment_confirmed) {
            $capture = Capture::get($auth->order_id, $this->_api_context);

            dd($capture);
        } else {
            //payment authorized but not yet captured
            //get payment authorized details

            //states == pending", "authorized", "partially_captured", "captured", "expired", "voided"

            $authorization = Authorization::get($auth->auth_id, $this->_api_context);


            dd($authorization);
        }
    }
}
