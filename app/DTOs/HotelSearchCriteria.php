<?php

namespace App\DTOs;

class HotelSearchCriteria
{
    /**
     * @param  array<int, int>  $childAges
     */
    public function __construct(
        public readonly string $destination,
        public readonly string $checkIn,
        public readonly string $checkOut,
        public readonly int $rooms,
        public readonly int $adults,
        public readonly int $children,
        public readonly array $childAges,
        public readonly string $currency = 'USD',
        public readonly int $maxHotels = 50,
    ) {}

    public static function fromArray(array $data): self
    {
        $children = max(0, (int) ($data['children'] ?? 0));
        $childAges = $data['childAges'] ?? $data['child_ages'] ?? [];

        if ($children > 0 && count($childAges) < $children) {
            $childAges = array_pad(
                array_map('intval', $childAges),
                $children,
                8
            );
        }

        return new self(
            destination: strtoupper(trim($data['destination'] ?? 'TZ_ALL')),
            checkIn: $data['checkIn'] ?? $data['check_in'] ?? '',
            checkOut: $data['checkOut'] ?? $data['check_out'] ?? '',
            rooms: max(1, (int) ($data['rooms'] ?? 1)),
            adults: max(1, (int) ($data['adults'] ?? 2)),
            children: $children,
            childAges: array_slice(array_map('intval', $childAges), 0, $children),
            currency: strtoupper($data['currency'] ?? config('hotels.defaults.currency', 'USD')),
            maxHotels: (int) ($data['maxHotels'] ?? $data['max'] ?? config('hotels.defaults.max_results', 50)),
        );
    }

    public function cacheKey(): string
    {
        return 'hotel_search:v1:' . md5(json_encode([
            $this->destination,
            $this->checkIn,
            $this->checkOut,
            $this->rooms,
            $this->adults,
            $this->children,
            $this->childAges,
            $this->currency,
            $this->maxHotels,
            config('hotels.provider', 'hotelbeds'),
        ]));
    }

    public function destinationMeta(): array
    {
        return config('hotels.destinations.' . $this->destination, [
            'code' => $this->destination,
            'name' => $this->destination,
            'country' => 'Tanzania',
        ]);
    }

    /**
     * @return array<int, string>
     */
    public function destinationCodes(): array
    {
        $meta = $this->destinationMeta();
        $codes = $meta['codes'] ?? null;

        if (is_array($codes) && $codes !== []) {
            return array_values(array_map(
                fn (string $code) => strtoupper(trim($code)),
                $codes
            ));
        }

        return [strtoupper((string) ($meta['code'] ?? $this->destination))];
    }

    public function withDestination(string $destination, ?int $maxHotels = null): self
    {
        return new self(
            destination: strtoupper(trim($destination)),
            checkIn: $this->checkIn,
            checkOut: $this->checkOut,
            rooms: $this->rooms,
            adults: $this->adults,
            children: $this->children,
            childAges: $this->childAges,
            currency: $this->currency,
            maxHotels: $maxHotels ?? $this->maxHotels,
        );
    }

    public function toArray(): array
    {
        return [
            'destination' => $this->destination,
            'checkIn' => $this->checkIn,
            'checkOut' => $this->checkOut,
            'rooms' => $this->rooms,
            'adults' => $this->adults,
            'children' => $this->children,
            'childAges' => $this->childAges,
            'currency' => $this->currency,
            'maxHotels' => $this->maxHotels,
        ];
    }
}
