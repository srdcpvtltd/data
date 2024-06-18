@component('mail::message')
{!! trim(preg_replace('/\h+/', ' ', trans('email.order_created.message', [
'url' => $url,
'site_name' => setting('app.name', 'ChargePanda')
]))) !!}
@endcomponent
