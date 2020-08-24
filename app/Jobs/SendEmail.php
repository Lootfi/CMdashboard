<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Postmark\PostmarkClient;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mailer;

    protected $client;
    protected $template;
    protected $batch;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client, $template, $batch = false)
    {
        $this->client = $client;
        $this->template = $template;
        $this->batch = $batch;

        $this->mailer = new PostmarkClient(env('POSTMARK_SECRET'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->mailer->sendEmailWithTemplate('support@contactmajor.com', 'test@blackhole.postmarkapp.com', $this->template, ['name' => 'Lotfi']);
    }
}
