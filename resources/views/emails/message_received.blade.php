@component('mail::message')
    {!! trim(preg_replace('/\h+/', ' ', trans('email.message_received.message', [
    'receiver' => $receiver,
    'sender' => $sender,
    'order_id' => $order_id,
    'url' => $url,
    'site_name' => setting('app.name', 'ChargePanda')
    ]))) !!}
@endcomponent
