@component('mail::message')
{!! trim(preg_replace('/\h+/', ' ', trans('email.verification.message', [
'name' => $user->name,
'url' => $verificationUrl,
'site_name' => setting('app.name', 'ChargePanda')
]))) !!}
@endcomponent
