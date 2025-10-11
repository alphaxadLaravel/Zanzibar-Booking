<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Amadeus API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the Amadeus Flight API.
    | You need to register at https://developers.amadeus.com to get your
    | API credentials (API Key and API Secret).
    |
    */

    'api_key' => env('AMADEUS_API_KEY', ''),
    
    'api_secret' => env('AMADEUS_API_SECRET', ''),
    
    /*
    | Environment: 'test' or 'production'
    | Test environment is free with 10,000 requests/month
    */
    'environment' => env('AMADEUS_ENV', 'test'),
    
    /*
    | Default search parameters
    */
    'defaults' => [
        'max_results' => 50,
        'currency' => 'USD',
        'travel_class' => 'ECONOMY', // ECONOMY, PREMIUM_ECONOMY, BUSINESS, FIRST
        'non_stop' => false,
    ],

    /*
    | Tanzania Airports (commonly used)
    */
    'tanzania_airports' => [
        'ZNZ' => 'Zanzibar - Abeid Amani Karume International',
        'DAR' => 'Dar es Salaam - Julius Nyerere International',
        'JRO' => 'Kilimanjaro International',
        'MWZ' => 'Mwanza Airport',
        'TBO' => 'Tabora Airport',
        'DOD' => 'Dodoma Airport',
    ],

    /*
    | Popular airlines operating in Tanzania
    */
    'tanzania_airlines' => [
        'TC' => 'Air Tanzania',
        'PW' => 'Precision Air',
        'ET' => 'Ethiopian Airlines',
        'KQ' => 'Kenya Airways',
        'EK' => 'Emirates',
        'QR' => 'Qatar Airways',
        'TK' => 'Turkish Airlines',
    ],

];

