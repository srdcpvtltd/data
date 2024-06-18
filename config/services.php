<?php
$base = isset($_SERVER['REQUEST_SCHEME']) ? ltrim($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].str_replace('index.php', '', $_SERVER['PHP_SELF']), '/') : '';
return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    
    
    'twitter' => [
        'client_id'     => '',
        'client_secret' => '',
        'redirect'      => $base.'auth/twitter/callback',
    ],
    
    
    'facebook' => [
        'client_id'     => '',
        'client_secret' => '',
        'redirect'      => $base.'auth/facebook/callback',
    ],
    
    
    'envato' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => $base.'auth/envato/callback'
    ],


];
