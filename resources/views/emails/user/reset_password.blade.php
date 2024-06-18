@component('mail::message')
{!! trim(preg_replace('/\h+/', ' ', trans('email.reset_password.message', [
'username' => $username,
'url' => $url,
'site_name' => setting('app.name', 'ChargePanda')
]))) !!}
@endcomponent
