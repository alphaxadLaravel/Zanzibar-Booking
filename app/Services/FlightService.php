<?php

namespace App\Services;

use App\DTOs\FlightSearchCriteria;
use App\Services\Flights\FlightSearchService;
use App\Services\Flights\TravelPayoutsApiService;

class FlightService
{
    public function __construct(
        protected FlightSearchService $searchService,
        protected TravelPayoutsApiService $travelPayouts,
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
        return $this->travelPayouts->searchLocations($keyword, $countryCode, $subTypes, $limit);
    }
}
