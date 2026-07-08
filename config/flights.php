<?php

return [

    'cache_ttl' => (int) env('FLIGHT_SEARCH_CACHE_TTL', 300),

    'defaults' => [
        'max_results' => 50,
        'currency' => 'USD',
        'travel_class' => 'ECONOMY',
    ],

    'travelpayouts' => [
        'token' => env('TRAVELPAYOUTS_TOKEN'),
        'marker' => env('TRAVELPAYOUTS_MARKER'),
        'search_url' => env('TRAVELPAYOUTS_SEARCH_URL', 'https://api.travelpayouts.com/aviasales/v3/prices_for_dates'),
        'autocomplete_url' => env('TRAVELPAYOUTS_AUTOCOMPLETE_URL', 'https://autocomplete.travelpayouts.com/places2'),
        'affiliate_base_url' => env('TRAVELPAYOUTS_AFFILIATE_URL', 'https://www.aviasales.com'),
        'locale' => env('TRAVELPAYOUTS_LOCALE', 'en'),
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
        'per_route' => (int) env('FLIGHT_FEATURED_PER_ROUTE', 2),
        'days_ahead' => (int) env('FLIGHT_FEATURED_DAYS_AHEAD', 7),
        'cache_ttl' => (int) env('FLIGHT_FEATURED_CACHE_TTL', 1800),
    ],

    /*
    | Priority routes preloaded when users land on /flights (kept small for speed).
    | Users can search any route via the form.
    */
    'featured_routes' => [
        // Tanzania domestic
        ['ZNZ', 'DAR'], ['DAR', 'ZNZ'],
        ['ZNZ', 'JRO'], ['JRO', 'ZNZ'],
        ['DAR', 'JRO'], ['JRO', 'DAR'],
        ['ZNZ', 'MWZ'], ['DAR', 'MWZ'],

        // East Africa
        ['ZNZ', 'NBO'], ['NBO', 'ZNZ'],
        ['DAR', 'NBO'], ['ZNZ', 'ADD'], ['ADD', 'ZNZ'],
        ['ZNZ', 'JNB'], ['JNB', 'ZNZ'],
        ['DAR', 'EBB'], ['EBB', 'ZNZ'],

        // International hubs
        ['ZNZ', 'DXB'], ['DXB', 'ZNZ'],
        ['DAR', 'DOH'], ['ZNZ', 'IST'], ['IST', 'ZNZ'],
        ['ZNZ', 'LHR'], ['LHR', 'ZNZ'],
        ['DAR', 'AMS'], ['ZNZ', 'CDG'],
    ],

];
