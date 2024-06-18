<?php

namespace App\Listeners\User;

use App\Models\Order;
use App\Models\OrderMeta;
use App\Notifications\User\WelcomeNewUserNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;

class UserEventSubscriber
{

    /**
     * Fires when a user registered.
     *
     * @param $event
     */
    public function onUserRegister($event)
    {
        if (!auth()->check()) {
            $event->user->notify(new WelcomeNewUserNotification());
        } elseif (request()->has('welcome_email')) {
            $event->user->notify(new WelcomeNewUserNotification());
        }

        // assign guest orders to the related user
        $orderIds = OrderMeta::where('key', 'billing_email')->where('value', $event->user->email)->pluck('order_id');

        if ($orderIds->count()) {
            Order::whereIn('id', $orderIds)->update(['user_id' => $event->user->id]);
        }
    }

    public function onUserLogin($event)
    {
        $event->user->last_login = now();
        $event->user->save();
    }


    public function subscribe($events)
    {
        return [
            Login::class => 'onUserLogin',
            Registered::class => 'onUserRegister',
        ];
    }
}
