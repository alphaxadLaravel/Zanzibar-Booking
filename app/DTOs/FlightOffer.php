<?php

namespace App\DTOs;

class FlightOffer
{
    public function __construct(
        public readonly string $id,
        public readonly string $flightNumber,
        public readonly string $airline,
        public readonly string $airlineCode,
        public readonly ?string $airlineLogo,
        public readonly array $departure,
        public readonly array $arrival,
        public readonly string $duration,
        public readonly int $stops,
        public readonly string $cabinClass,
        public readonly ?string $baggage,
        public readonly string $refundable,
        public readonly float $price,
        public readonly string $currency,
        public readonly string $affiliateName,
        public readonly string $affiliateUrl,
        public readonly string $availabilityStatus = 'available',
        public readonly string $availabilityLabel = 'Available for this date',
        public readonly ?string $priceExpiresAt = null,
        public readonly ?string $foundAt = null,
        public readonly ?array $raw = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'flight_number' => $this->flightNumber,
            'airline' => $this->airline,
            'airline_code' => $this->airlineCode,
            'airline_logo' => $this->airlineLogo,
            'departure' => $this->departure,
            'arrival' => $this->arrival,
            'duration' => $this->duration,
            'stops' => $this->stops,
            'cabin_class' => $this->cabinClass,
            'baggage' => $this->baggage,
            'refundable' => $this->refundable,
            'price' => $this->price,
            'currency' => $this->currency,
            'affiliate_name' => $this->affiliateName,
            'affiliate_url' => $this->affiliateUrl,
            'availability_status' => $this->availabilityStatus,
            'availability_label' => $this->availabilityLabel,
            'price_expires_at' => $this->priceExpiresAt,
            'found_at' => $this->foundAt,
            'offer_data' => $this->raw,
        ];
    }
}
