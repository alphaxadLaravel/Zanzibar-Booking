<?php

return [

    'enabled' => filter_var(env('HOTEL_WHOLESALER_ENABLED', true), FILTER_VALIDATE_BOOL),

    'cache_ttl' => (int) env('HOTEL_SEARCH_CACHE_TTL', 300),

    'checkout_ttl' => (int) env('HOTEL_CHECKOUT_CACHE_TTL', 1800),

    'defaults' => [
        'max_results' => (int) env('HOTEL_SEARCH_MAX_RESULTS', 200),
        'currency' => env('HOTEL_DEFAULT_CURRENCY', 'USD'),
        'rooms' => 1,
        'adults' => 2,
        'children' => 0,
    ],

    'provider' => env('HOTEL_PROVIDER', 'hotelbeds'),

    'hotelbeds' => [
        'api_key' => env('HOTELBEDS_API_KEY'),
        'secret' => env('HOTELBEDS_SECRET'),
        'api_url' => rtrim(env('HOTELBEDS_API_URL', 'https://api.test.hotelbeds.com'), '/'),
        'environment' => env('HOTELBEDS_ENV', 'test'),
        'timeout' => (int) env('HOTELBEDS_TIMEOUT', 30),
        'create_bookings' => filter_var(env('HOTELBEDS_CREATE_BOOKINGS', true), FILTER_VALIDATE_BOOL),
        'image_base_url' => rtrim(env('HOTELBEDS_IMAGE_BASE_URL', 'https://photos.hotelbeds.com/giata'), '/') . '/',
        'content_cache_ttl' => (int) env('HOTELBEDS_CONTENT_CACHE_TTL', 86400),
    ],

    /*
    | Markup added on top of wholesaler net rate (customer pays supplier + markup).
    */
    'markup' => [
        'percent' => (float) env('HOTEL_MARKUP_PERCENT', 10),
        'fixed' => (float) env('HOTEL_MARKUP_FIXED', 0),
    ],

    /*
    | Hotelbeds destination codes (from Content API). Override via env if needed.
    */
    'destinations' => [
        'TZ_ALL' => [
            'code' => 'TZ_ALL',
            'name' => 'Tanzania & Zanzibar',
            'country' => 'Tanzania',
            'codes' => ['ZNZ', 'DAR'],
        ],
        'ZNZ' => [
            'code' => env('HOTELBEDS_DEST_ZNZ', 'ZNZ'),
            'name' => 'Zanzibar',
            'country' => 'Tanzania',
        ],
        'DAR' => [
            'code' => env('HOTELBEDS_DEST_DAR', 'DAR'),
            'name' => 'Dar es Salaam',
            'country' => 'Tanzania',
        ],
    ],

    /*
    | Approximate bounds for Tanzania mainland + Zanzibar (lat/lng).
    */
    'tanzania_bounds' => [
        'lat_min' => -12.5,
        'lat_max' => -0.5,
        'lng_min' => 29.0,
        'lng_max' => 40.9,
    ],

    'destination_options' => [
        'Tanzania & Zanzibar' => [
            'TZ_ALL' => 'All Tanzania & Zanzibar',
            'ZNZ' => 'Zanzibar',
            'DAR' => 'Dar es Salaam',
        ],
    ],

];
