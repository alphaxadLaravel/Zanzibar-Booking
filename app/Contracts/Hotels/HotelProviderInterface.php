<?php

namespace App\Contracts\Hotels;

use App\DTOs\HotelOffer;
use App\DTOs\HotelSearchCriteria;

interface HotelProviderInterface
{
    public function getName(): string;

    /**
     * @return HotelOffer[]
     */
    public function search(HotelSearchCriteria $criteria): array;

    /**
     * @return array<string, mixed>
     */
    public function checkRate(string $rateKey): array;

    /**
     * @param  array<int, array<string, mixed>>  $rooms
     * @return array<string, mixed>
     */
    public function createBooking(
        string $clientReference,
        array $holder,
        array $rooms,
        ?string $remark = null,
    ): array;
}
