<?php

return [

    'cache_ttl' => (int) env('FLIGHT_SEARCH_CACHE_TTL', 300),

    'defaults' => [
        'max_results' => 50,
        'currency' => 'USD',
        'travel_class' => 'ECONOMY',
    ],

    'duffel' => [
        'access_token' => env('DUFFEL_ACCESS_TOKEN'),
        'api_url' => env('DUFFEL_API_URL', 'https://api.duffel.com'),
        'api_version' => env('DUFFEL_API_VERSION', 'v2'),
        'supplier_timeout' => (int) env('DUFFEL_SUPPLIER_TIMEOUT', 15000),
        'featured_supplier_timeout' => (int) env('DUFFEL_FEATURED_SUPPLIER_TIMEOUT', 8000),
        'order_type' => env('DUFFEL_ORDER_TYPE', 'instant'),
        'payment_type' => env('DUFFEL_PAYMENT_TYPE', 'balance'),
        'create_orders' => filter_var(env('DUFFEL_CREATE_ORDERS', false), FILTER_VALIDATE_BOOL),
    ],

    'airports' => [
        'ZNZ' => 'Zanzibar - Abeid Amani Karume International',
        'DAR' => 'Dar es Salaam - Julius Nyerere International',
        'JRO' => 'Kilimanjaro International',
        'MWZ' => 'Mwanza Airport',
        'TBO' => 'Tabora Airport',
        'DOD' => 'Dodoma Airport',
    ],

    /*
    | Airport dropdown options grouped for the search form.
    */
    'airport_options' => [
        'Tanzania' => [
            'ZNZ' => 'Zanzibar (ZNZ)',
            'DAR' => 'Dar es Salaam (DAR)',
            'JRO' => 'Kilimanjaro (JRO)',
            'MWZ' => 'Mwanza (MWZ)',
            'DOD' => 'Dodoma (DOD)',
            'TBO' => 'Tabora (TBO)',
        ],
        'East Africa' => [
            'NBO' => 'Nairobi (NBO)',
            'MBA' => 'Mombasa (MBA)',
            'KGL' => 'Kigali (KGL)',
            'EBB' => 'Entebbe (EBB)',
            'ADD' => 'Addis Ababa (ADD)',
            'JNB' => 'Johannesburg (JNB)',
            'CPT' => 'Cape Town (CPT)',
        ],
        'Middle East & Asia' => [
            'DXB' => 'Dubai (DXB)',
            'DOH' => 'Doha (DOH)',
            'AUH' => 'Abu Dhabi (AUH)',
            'IST' => 'Istanbul (IST)',
            'BOM' => 'Mumbai (BOM)',
            'DEL' => 'Delhi (DEL)',
            'BKK' => 'Bangkok (BKK)',
            'SIN' => 'Singapore (SIN)',
        ],
        'Europe' => [
            'LHR' => 'London Heathrow (LHR)',
            'AMS' => 'Amsterdam (AMS)',
            'FRA' => 'Frankfurt (FRA)',
            'CDG' => 'Paris Charles de Gaulle (CDG)',
            'FCO' => 'Rome (FCO)',
            'MUC' => 'Munich (MUC)',
        ],
        'Americas' => [
            'JFK' => 'New York JFK (JFK)',
            'IAD' => 'Washington Dulles (IAD)',
            'YYZ' => 'Toronto (YYZ)',
        ],
    ],

    'airlines' => [
        'TC' => 'Air Tanzania',
        'PW' => 'Precision Air',
        'ET' => 'Ethiopian Airlines',
        'KQ' => 'Kenya Airways',
        'EK' => 'Emirates',
        'QR' => 'Qatar Airways',
        'TK' => 'Turkish Airlines',
    ],

    /*
    | Preload featured flights on page load (Tanzania, Africa, and international).
    */
    'featured' => [
        'per_route' => (int) env('FLIGHT_FEATURED_PER_ROUTE', 3),
        'days_ahead' => (int) env('FLIGHT_FEATURED_DAYS_AHEAD', 7),
        'cache_ttl' => (int) env('FLIGHT_FEATURED_CACHE_TTL', 1800),
        'max_routes' => (int) env('FLIGHT_FEATURED_MAX_ROUTES', 8),
    ],

    /*
    | Markup added on top of Duffel supplier total (what you charge the customer).
    | Duffel is still paid the supplier fare from your balance; the difference is your margin.
    */
    'markup' => [
        'percent' => (float) env('FLIGHT_MARKUP_PERCENT', 0),
        'fixed' => (float) env('FLIGHT_MARKUP_FIXED', 0),
    ],

    /*
    | Priority routes preloaded when users land on /flights.
    | Duffel searches are slower than cached affiliate APIs, so featured routes are capped.
    */
    'featured_routes' => [
        ['ZNZ', 'DAR'], ['DAR', 'ZNZ'],
        ['ZNZ', 'JRO'], ['JRO', 'ZNZ'],
        ['DAR', 'JRO'], ['JRO', 'DAR'],
        ['ZNZ', 'NBO'], ['NBO', 'ZNZ'],
        ['ZNZ', 'DXB'], ['DXB', 'ZNZ'],
        ['ZNZ', 'ADD'], ['ADD', 'ZNZ'],
        ['ZNZ', 'IST'], ['IST', 'ZNZ'],
        ['ZNZ', 'LHR'], ['LHR', 'ZNZ'],
    ],

];
