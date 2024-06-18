@component('mail::message')
    {!! trim(preg_replace('/\h+/', ' ', trans('email.welcome.message', [
    'name' => $user->name,
    'url' => url('/'),
    'site_name' => setting('app.name', 'ChargePanda')
    ]))) !!}
@endcomponent
