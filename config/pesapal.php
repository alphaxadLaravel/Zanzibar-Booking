<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pesapal API Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your Pesapal API credentials here. These can be obtained from
    | your Pesapal merchant dashboard.
    |
    */

    'consumer_key' => env('PESAPAL_CONSUMER_KEY'),
    'consumer_secret' => env('PESAPAL_CONSUMER_SECRET'),
    'ipn_id' => env('PESAPAL_IPN_ID'),

    /*
    |--------------------------------------------------------------------------
    | Pesapal Environment
    |--------------------------------------------------------------------------
    |
    | Set to 'sandbox' for testing or 'live' for production
    |
    */

    'environment' => env('PESAPAL_ENV', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | Default currency for payments (USD, KES, UGX, TZS, etc.)
    |
    */

    'currency' => env('PESAPAL_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Callback Route
    |--------------------------------------------------------------------------
    |
    | Route name for payment callback (the library uses route() to generate the URL)
    |
    */

    'callback_route' => env('PESAPAL_CALLBACK_ROUTE', 'payment.success'),

    /*
    |--------------------------------------------------------------------------
    | Callback URLs (optional - legacy, use callback_route instead)
    |--------------------------------------------------------------------------
    |
    | URLs for payment callbacks (used as fallback)
    |
    */

    'callback_url' => env('PESAPAL_CALLBACK_URL', env('APP_URL') . '/payment/success'),
    'notification_url' => env('PESAPAL_NOTIFICATION_URL', env('APP_URL') . '/payment/confirmation'),

];

