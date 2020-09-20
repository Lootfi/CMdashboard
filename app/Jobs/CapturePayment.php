<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

use PayPal\Api\Amount;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;

class CapturePayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $client;
    public $tries = 25;
    public $timeout = 120;
    protected $_api_context;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($artist)
    {
        $this->client = $artist;

        //paypal config
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret']
        ));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $auth = $this->client->payment_auth;

        if ($auth->stripe_customer_id) {
            $pi_id = explode('stripe_', $this->client->payment_method)[1];
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $captured = $stripe->paymentIntents->capture($pi_id);

            if ($captured->status == "succeeded") {
                $this->client->payment_confirmed = true;
                $this->client->updated_at = now();
                $this->client->save();
                // $auth->delete(); //deletes order_id and auth_id
                $this->delete();
            } else {
                $this->release();
            }
            return;
        }

        try {
            //code...

            $authorization = Authorization::get($auth->auth_id, $this->_api_context);
            $amt = new Amount();
            $amt->setCurrency($authorization->getAmount()->getCurrency())->setTotal($authorization->getAmount()->getTotal());

            $capture = new Capture();
            $capture->setAmount($amt);

            $getCapture = $authorization->capture($capture, $this->_api_context);

            if ($getCapture->getState() == "completed") {
                $this->client->payment_confirmed = true;
                $this->client->payment_method = 'paypal_' . $getCapture->getId();
                $this->client->updated_at = now();
                $this->client->save();
                // $auth->delete(); //deletes order_id and auth_id
                $this->delete();
                return;
            }
        } catch (\Exception $ex) {
            if ($authorization->getState() == "captured") {
                $this->fail();
            } else {
                $this->release();
            }
        }
    }


    public function retryUntil()
    {
        return now()->addSeconds(20);
    }
}
