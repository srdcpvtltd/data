@component('mail::message')
@if($status == 'processing')
{!! trim(preg_replace('/\h+/', ' ', trans('email.order_processing.message', [
'url' => $url,
'site_name' => setting('app.name', 'ChargePanda')
]))) !!}
@else
{!! trim(preg_replace('/\h+/', ' ', trans('email.order_completed.message', [
'url' => $url,
'site_name' => setting('app.name', 'ChargePanda')
]))) !!}
@endif
@endcomponent
