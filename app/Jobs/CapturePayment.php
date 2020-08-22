<?php

namespace App\Jobs;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Jobs\SyncJob;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\Uuid;

class CapturePayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $client;
    public $tries = 5;
    public $timeout = 20;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($artist)
    {
        $this->client = $artist;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $auth = $this->client->payment_auth;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v2/payments/authorizations/' . $auth->auth_id . '/capture');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . env('PAYPAL_TOKEN');
        $headers[] = 'Paypal-Request-Id: ' . Uuid::uuid1()->toString();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            $this->release();
        }
        echo 'FIRST ECHO: ' . json_encode($result);
        echo 'STATUS: "' . json_decode($result)->status . '"';

        if (json_decode($result)->status == "COMPLETED") {
            echo 'STATUS: ' . json_decode($result)->status;
            $this->client->payment_confirmed = true;
            $this->client->payment_method = 'paypal_' . json_decode($result)->id;
            $this->client->updated_at = now();
            $this->client->save();
            $this->delete();
            return;
        }
        if (json_decode($result)->details) {
            echo "ERROR: " . json_decode($result)->details[0]->issue;
            if (json_decode($result)->details[0]->issue == "AUTHORIZATION_ALREADY_CAPTURED") {
                echo "AUTHORIZATION_ALREADY_CAPTURED";
                $this->fail();
            } else $this->release();
        }

        curl_close($ch);
    }
}
