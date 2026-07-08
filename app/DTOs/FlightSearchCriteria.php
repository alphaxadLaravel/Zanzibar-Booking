<?php

namespace App\DTOs;

class FlightSearchCriteria
{
    public function __construct(
        public readonly string $tripType,
        public readonly string $origin,
        public readonly string $destination,
        public readonly string $departureDate,
        public readonly ?string $returnDate,
        public readonly int $adults,
        public readonly int $children,
        public readonly int $infants,
        public readonly string $cabin,
        public readonly string $currency = 'USD',
        public readonly int $max = 50,
        public readonly bool $nonStop = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            tripType: $data['tripType'] ?? $data['trip_type'] ?? 'one_way',
            origin: strtoupper(trim($data['origin'] ?? '')),
            destination: strtoupper(trim($data['destination'] ?? '')),
            departureDate: $data['departureDate'] ?? $data['departure_date'] ?? '',
            returnDate: $data['returnDate'] ?? $data['return_date'] ?? null,
            adults: max(1, (int) ($data['adults'] ?? 1)),
            children: max(0, (int) ($data['children'] ?? 0)),
            infants: max(0, (int) ($data['infants'] ?? 0)),
            cabin: strtoupper($data['travelClass'] ?? $data['cabin'] ?? 'ECONOMY'),
            currency: strtoupper($data['currency'] ?? 'USD'),
            max: (int) ($data['max'] ?? 50),
            nonStop: (bool) ($data['nonStop'] ?? $data['non_stop'] ?? false),
        );
    }

    public function cacheKey(): string
    {
        return 'flight_search:' . md5(json_encode([
            $this->tripType,
            $this->origin,
            $this->destination,
            $this->departureDate,
            $this->returnDate,
            $this->adults,
            $this->children,
            $this->infants,
            $this->cabin,
            $this->currency,
            $this->max,
            $this->nonStop,
            'travelpayouts',
        ]));
    }

    public function toArray(): array
    {
        return [
            'tripType' => $this->tripType,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'departureDate' => $this->departureDate,
            'returnDate' => $this->returnDate,
            'adults' => $this->adults,
            'children' => $this->children,
            'infants' => $this->infants,
            'travelClass' => $this->cabin,
            'currency' => $this->currency,
            'max' => $this->max,
            'nonStop' => $this->nonStop,
        ];
    }
}
