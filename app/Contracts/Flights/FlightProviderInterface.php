<?php

namespace App\Contracts\Flights;

use App\DTOs\FlightOffer;
use App\DTOs\FlightSearchCriteria;

interface FlightProviderInterface
{
    public function getName(): string;

    /**
     * @return FlightOffer[]
     */
    public function search(FlightSearchCriteria $criteria): array;

    public function buildAffiliateUrl(FlightOffer $offer, FlightSearchCriteria $criteria): string;
}
