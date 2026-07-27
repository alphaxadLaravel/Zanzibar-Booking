<?php

namespace App\DTOs;

class HotelOffer
{
    /**
     * @param  array<int, array<string, mixed>>  $cancellationPolicies
     */
    public function __construct(
        public readonly string $id,
        public readonly string $rateKey,
        public readonly string $hotelCode,
        public readonly string $hotelName,
        public readonly string $destinationCode,
        public readonly string $destinationName,
        public readonly string $checkIn,
        public readonly string $checkOut,
        public readonly string $roomName,
        public readonly string $boardName,
        public readonly ?string $categoryCode,
        public readonly float $supplierTotal,
        public readonly float $markup,
        public readonly float $price,
        public readonly string $currency,
        public readonly string $rateType,
        public readonly int $rooms,
        public readonly int $adults,
        public readonly int $children,
        public readonly array $cancellationPolicies = [],
        public readonly ?string $latitude = null,
        public readonly ?string $longitude = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? md5($data['rate_key'] ?? $data['rateKey'] ?? uniqid('', true))),
            rateKey: (string) ($data['rate_key'] ?? $data['rateKey'] ?? ''),
            hotelCode: (string) ($data['hotel_code'] ?? $data['hotelCode'] ?? ''),
            hotelName: (string) ($data['hotel_name'] ?? $data['hotelName'] ?? ''),
            destinationCode: (string) ($data['destination_code'] ?? $data['destinationCode'] ?? ''),
            destinationName: (string) ($data['destination_name'] ?? $data['destinationName'] ?? ''),
            checkIn: (string) ($data['check_in'] ?? $data['checkIn'] ?? ''),
            checkOut: (string) ($data['check_out'] ?? $data['checkOut'] ?? ''),
            roomName: (string) ($data['room_name'] ?? $data['roomName'] ?? ''),
            boardName: (string) ($data['board_name'] ?? $data['boardName'] ?? ''),
            categoryCode: isset($data['category_code']) || isset($data['categoryCode'])
                ? (string) ($data['category_code'] ?? $data['categoryCode'])
                : null,
            supplierTotal: (float) ($data['supplier_total'] ?? $data['supplierTotal'] ?? 0),
            markup: (float) ($data['markup'] ?? 0),
            price: (float) ($data['price'] ?? 0),
            currency: strtoupper((string) ($data['currency'] ?? 'USD')),
            rateType: strtoupper((string) ($data['rate_type'] ?? $data['rateType'] ?? 'BOOKABLE')),
            rooms: (int) ($data['rooms'] ?? 1),
            adults: (int) ($data['adults'] ?? 2),
            children: (int) ($data['children'] ?? 0),
            cancellationPolicies: $data['cancellation_policies'] ?? $data['cancellationPolicies'] ?? [],
            latitude: isset($data['latitude']) ? (string) $data['latitude'] : null,
            longitude: isset($data['longitude']) ? (string) $data['longitude'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'rate_key' => $this->rateKey,
            'hotel_code' => $this->hotelCode,
            'hotel_name' => $this->hotelName,
            'destination_code' => $this->destinationCode,
            'destination_name' => $this->destinationName,
            'check_in' => $this->checkIn,
            'check_out' => $this->checkOut,
            'room_name' => $this->roomName,
            'board_name' => $this->boardName,
            'category_code' => $this->categoryCode,
            'supplier_total' => $this->supplierTotal,
            'markup' => $this->markup,
            'price' => $this->price,
            'currency' => $this->currency,
            'rate_type' => $this->rateType,
            'rooms' => $this->rooms,
            'adults' => $this->adults,
            'children' => $this->children,
            'cancellation_policies' => $this->cancellationPolicies,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
