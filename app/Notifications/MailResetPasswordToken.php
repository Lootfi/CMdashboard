<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Postmark\PostmarkClient;

class MailResetPasswordToken extends Notification
{
    use Queueable;
    public $token, $mailer;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->mailer = new PostmarkClient(env('POSTMARK_SECRET'));
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new \Coconuts\Mail\MailMessage)
            ->alias('lost_password')
            ->from('support@contactmajor.com')
            ->include([
                'link' => 'https://contactmajor.com/password/reset/?token=' . $this->token . '&email=' . $notifiable->email
            ]);
        // $this->mailer->sendEmailWithTemplate('support@contactmajor.com', 'test@blackhole.postmarkapp.com', 'reset-password', ['name' => "from database", 'link' => 'https://www.contactmajor.com/password/reset/?token=' . $this->token . '&email=' . $notifiable->email]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
