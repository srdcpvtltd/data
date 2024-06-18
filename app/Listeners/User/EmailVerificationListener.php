<?php

namespace App\Listeners\User;

use Illuminate\Auth\Events\Registered;
use \Illuminate\Auth\Listeners\SendEmailVerificationNotification as VerificationListner;

class EmailVerificationListener extends VerificationListner
{
    public function handle(Registered $event)
    {
        if (setting('email_verification') == 'on') {
            parent::handle($event);
        }
    }
}
