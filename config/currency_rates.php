<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base currency (all prices stored in this currency)
    |--------------------------------------------------------------------------
    */
    'base' => 'USD',

    /*
    |--------------------------------------------------------------------------
    | Exchange rates relative to base (USD). 1 USD = rate units of currency.
    | Update manually or run artisan command to fetch from API.
    | Set EXCHANGE_RATES_USE_API=true to fetch from Frankfurter so display rates
    | match "current day" rates (e.g. closer to what Pesapal shows). Use
    | EXCHANGE_RATES_CACHE_TTL_MINUTES=1 (default) to refresh every minute.
    |--------------------------------------------------------------------------
    */
    'rates' => [
        'USD' => 1.0,
        'TZS' => 2650.0,
        'KES' => 130.0,
        'UGX' => 3800.0,
        'ZAR' => 18.5,
        'GBP' => 0.79,
        'EUR' => 0.92,
        'AED' => 3.67,
        'INR' => 83.0,
        'CNY' => 7.24,
        'JPY' => 149.0,
        'CHF' => 0.88,
        'AUD' => 1.53,
        'CAD' => 1.36,
        'NGN' => 1580.0,
        'GHS' => 12.5,
        'MAD' => 10.0,
        'EGP' => 30.9,
        'RWF' => 1280.0,
        'BIF' => 2850.0,
        'ZMW' => 27.0,
        'MZN' => 63.9,
        'MWK' => 1750.0,
        'ETB' => 56.5,
        'SZL' => 18.5,
        'LSL' => 18.5,
        'NAD' => 18.5,
        'BWP' => 13.6,
        'MUR' => 45.0,
        'SCR' => 13.5,
        'PKR' => 278.0,
        'BDT' => 110.0,
        'LKR' => 325.0,
        'NPR' => 133.0,
        'THB' => 35.0,
        'MYR' => 4.72,
        'SGD' => 1.34,
        'PHP' => 56.0,
        'IDR' => 15700.0,
        'VND' => 24500.0,
        'KRW' => 1320.0,
        'TRY' => 32.2,
        'RUB' => 92.0,
        'BRL' => 4.97,
        'MXN' => 17.1,
        'ARS' => 875.0,
        'CLP' => 925.0,
        'COP' => 3950.0,
        'PEN' => 3.72,
        'SAR' => 3.75,
        'QAR' => 3.64,
        'KWD' => 0.31,
        'BHD' => 0.376,
        'OMR' => 0.385,
        'JOD' => 0.709,
        'ILS' => 3.68,
        'XAF' => 605.0,
        'XOF' => 605.0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache key and TTL for API-fetched rates (minutes).
    | TTL in minutes (default 1) keeps display rates close to Pesapal's current rate.
    |--------------------------------------------------------------------------
    */
    'cache_key' => 'currency_rates_usd',
    'cache_ttl_minutes' => env('EXCHANGE_RATES_CACHE_TTL_MINUTES', 1),

];
