@component('mail::message')
{!! trim(preg_replace('/\h+/', ' ', trans('email.pre_order_query.message', array_merge($data, [
'site_name' => setting('app.name', 'ChargePanda')
])))) !!}
@endcomponent
