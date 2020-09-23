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

class PaypalCapture implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $client, $auth_id;
    public $tries = 25;
    public $timeout = 120;
    protected $_api_context;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($artist_id, $auth_id)
    {
        $this->client = Artist::where('id', $artist_id);
        $this->auth_id = $auth_id;

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
        try {
            $authorization = Authorization::get($this->auth_id, $this->_api_context);
            if ($authorization->getState() == "captured") {
                $this->client->payment_confirmed = true;
                $this->client->updated_at = now();
                $this->client->save();
                $this->delete();
            } else {
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
                }
            }
        } catch (\Exception $ex) {
            $this->release();
        }
    }
}
