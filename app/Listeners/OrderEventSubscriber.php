<?php

namespace App\Listeners;

use App\Mail\Order\OrderCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class OrderEventSubscriber
{

    public function onOrderCreated ( $event ) {

        $order = $event->order;

        Mail::to($order->user->email)->send(new OrderCreated($order));

    }


    public function subscribe( $events ) {

        $events->listen(
            'App\Events\Order\OrderCreated',
            'App\Listeners\OrderEventSubscriber@onOrderCreated'
        );
    }
}
