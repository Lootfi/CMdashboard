<?php

namespace App\Http\Controllers\Api\Front\Payments;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

use PayPal\Api\Amount;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;


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
        $user = $request->user('clients');

        if ($user->payment_confirmed) {
            //get payment captured details
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v2/payments/captures/' . explode('_', $user->payment_method)[1]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . env('PAYPAL_TOKEN');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            $info = json_decode($result); //id, amount = {value, currency_code}, final_capture(bool), status,create_time, links

            return response()->json($info);
        } else {
            //payment authorized but not yet captured //
            //get payment authorized details

            $auth = $user->payment_auth;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v2/payments/authorizations/' . $auth->auth_id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . env('PAYPAL_TOKEN');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            $info = json_decode($result); //id, amount = {value, currency_code}, status, create_time, expiration_time, links

            return response()->json($info);
        }
    }
}
