<?php

namespace App\Services;

use App\DTOs\FlightSearchCriteria;
use App\Services\Flights\FlightSearchService;
use App\Services\Flights\DuffelApiService;

class FlightService
{
    public function __construct(
        protected FlightSearchService $searchService,
        protected DuffelApiService $duffelApi,
    ) {}

    public function searchFlights(array $params): array
    {
        $criteria = FlightSearchCriteria::fromArray($params);

        return $this->searchService->search($criteria);
    }

    public function searchLocations(
        string $keyword,
        ?string $countryCode = null,
        array $subTypes = ['AIRPORT', 'CITY'],
        int $limit = 10,
        string $view = 'FULL',
    ): array {
        $locations = $this->duffelApi->searchPlaces($keyword, $limit);

        if ($countryCode) {
            $locations = array_values(array_filter(
                $locations,
                fn ($location) => strtoupper($location['countryCode'] ?? '') === strtoupper($countryCode)
            ));
        }

        if (! empty($subTypes)) {
            $allowed = array_map('strtoupper', $subTypes);
            $locations = array_values(array_filter(
                $locations,
                fn ($location) => in_array(strtoupper($location['subType'] ?? ''), $allowed, true)
            ));
        }

        return array_slice($locations, 0, $limit);
    }
}
