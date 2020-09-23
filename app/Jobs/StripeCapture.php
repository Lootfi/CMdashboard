<?php

namespace App\Jobs;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StripeCapture implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $client;
    public $tries = 15;
    public $timeout = 120;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($artist_id)
    {
        $this->client = Artist::where('id', $artist_id)->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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
    }
}
