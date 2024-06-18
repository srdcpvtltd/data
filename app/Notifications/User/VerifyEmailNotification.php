<?php

namespace App\Notifications\User;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    /**
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage|mixed
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage())
            ->subject(trans('email.verification.subject'))
            ->markdown('emails.user.verify', [
                'user' => $notifiable,
                'verificationUrl' => $verificationUrl
            ]);
    }
}
