<?php

namespace App\Http\Wrappers;

use Postmark\PostmarkClient;

class Mailer
{
    protected $mailer;

    function __construct()
    {
        $this->mailer = new PostmarkClient(env('POSTMARK_SECRET'));
    }


    public function send($request)
    {
        $this->mailer->createTemplate($request->name, $request->subject, $request->htmlBody, $request->textBody);
        $sendResult = $this->mailer->sendEmail("support@contactmajor.com", "test@blackhole.postmarkapp.com", "Hello from Postmark!", "This is just a friendly 'hello' from your friends at Postmark.", "This is just a friendly 'hello' from your friends at Postmark.");
    }
}
