<?php

namespace App\Services\Flights;

use App\DTOs\FlightSearchCriteria;
use App\Models\FlightSearch;
use App\Repositories\FlightSearchRepository;
use App\Support\FlightOfferMapper;
use App\Support\VisitorContext;

class AffiliateTrackingService
{
    public function __construct(
        protected FlightSearchRepository $searches,
    ) {}

    public function logSearch(FlightSearchCriteria $criteria, int $resultsCount): FlightSearch
    {
        $visitor = VisitorContext::capture();

        return $this->searches->create([
            'user_id' => $visitor['user_id'],
            'trip_type' => $criteria->tripType,
            'origin_code' => $criteria->origin,
            'origin_name' => FlightOfferMapper::airportCity($criteria->origin),
            'destination_code' => $criteria->destination,
            'destination_name' => FlightOfferMapper::airportCity($criteria->destination),
            'departure_date' => $criteria->departureDate,
            'return_date' => $criteria->returnDate,
            'adults' => $criteria->adults,
            'children' => $criteria->children,
            'infants' => $criteria->infants,
            'travel_class' => $criteria->cabin,
            'results_count' => $resultsCount,
            'session_id' => $visitor['session_id'],
            'ip_address' => $visitor['ip_address'],
            'country' => $visitor['country'],
            'device' => $visitor['device'],
            'browser' => $visitor['browser'],
            'operating_system' => $visitor['operating_system'],
        ]);
    }

    public function logClick(array $payload): \App\Models\FlightClick
    {
        $visitor = VisitorContext::capture();
        $fingerprint = md5(implode('|', [
            $visitor['session_id'],
            $payload['flight_number'] ?? '',
            $payload['origin'] ?? '',
            $payload['destination'] ?? '',
            $payload['affiliate_url'] ?? '',
        ]));

        $cacheKey = 'flight_click_throttle:' . $fingerprint;

        if (! cache()->add($cacheKey, true, now()->addSeconds(5))) {
            throw new \RuntimeException('Please wait a moment before clicking again.');
        }

        return \App\Models\FlightClick::create([
            'flight_search_id' => $payload['flight_search_id'] ?? null,
            'user_id' => $visitor['user_id'],
            'airline' => $payload['airline'] ?? null,
            'flight_number' => $payload['flight_number'] ?? null,
            'origin' => strtoupper($payload['origin']),
            'destination' => strtoupper($payload['destination']),
            'price' => $payload['price'] ?? null,
            'currency' => $payload['currency'] ?? 'USD',
            'affiliate_name' => $payload['affiliate_name'],
            'affiliate_url' => $payload['affiliate_url'],
            'clicked_at' => now(),
            'ip_address' => $visitor['ip_address'],
            'country' => $visitor['country'],
            'device' => $visitor['device'],
            'browser' => $visitor['browser'],
            'operating_system' => $visitor['operating_system'],
        ]);
    }
}
