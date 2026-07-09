<?php

namespace App\Support;

use App\DTOs\FlightOffer;

class FlightOfferMapper
{
    public static function segment(string $airport, string $datetime): array
    {
        $timestamp = strtotime($datetime) ?: time();

        return [
            'airport' => strtoupper($airport),
            'city' => self::airportCity($airport),
            'time' => date('H:i', $timestamp),
            'date' => date('Y-m-d', $timestamp),
            'datetime' => date('c', $timestamp),
        ];
    }

    public static function airlineName(string $code): string
    {
        $airlines = config('flights.airlines', []);

        return $airlines[$code] ?? $code;
    }

    public static function airlineLogoUrl(string $code): string
    {
        return 'https://pics.avs.io/99/36/' . strtoupper($code) . '.png';
    }

    public static function airportCity(string $code): string
    {
        $airports = config('flights.airports', []);

        if (isset($airports[$code])) {
            return explode(' - ', $airports[$code])[0];
        }

        return strtoupper($code);
    }

    public static function formatMinutes(int $minutes): string
    {
        if ($minutes <= 0) {
            return 'N/A';
        }

        $hours = intdiv($minutes, 60);
        $remaining = $minutes % 60;

        if ($hours > 0 && $remaining > 0) {
            return "{$hours}h {$remaining}m";
        }

        if ($hours > 0) {
            return "{$hours}h";
        }

        return "{$remaining}m";
    }

    /**
     * Derive bookability from a flight offer payload.
     *
     * @return array{status: string, label: string, expires_at: ?string, found_at: ?string}
     */
    public static function resolveAvailability(array $item): array
    {
        $price = (float) ($item['price'] ?? 0);
        $expiresAt = $item['expires_at'] ?? null;
        $foundAt = $item['found_at'] ?? null;

        if ($price <= 0) {
            return [
                'status' => 'unavailable',
                'label' => 'Not available',
                'expires_at' => $expiresAt,
                'found_at' => $foundAt,
            ];
        }

        $label = 'Available for this date';

        if ($expiresAt && strtotime($expiresAt) !== false && strtotime($expiresAt) > time()) {
            $label = 'Available — book soon';
        }

        return [
            'status' => 'available',
            'label' => $label,
            'expires_at' => $expiresAt,
            'found_at' => $foundAt,
        ];
    }

    public static function formatAvailabilityExpiry(?string $expiresAt): ?string
    {
        if (! $expiresAt || strtotime($expiresAt) === false) {
            return null;
        }

        return date('D, d M Y H:i', strtotime($expiresAt)) . ' UTC';
    }

    /**
     * @return array{status: string, label: string, expires_at: ?string, found_at: ?string}
     */
    public static function resolveDuffelAvailability(array $offer): array
    {
        $price = (float) ($offer['total_amount'] ?? 0);
        $expiresAt = $offer['expires_at'] ?? null;

        if ($price <= 0) {
            return [
                'status' => 'unavailable',
                'label' => 'Not available',
                'expires_at' => $expiresAt,
                'found_at' => null,
            ];
        }

        if ($expiresAt && strtotime($expiresAt) !== false && strtotime($expiresAt) <= time()) {
            return [
                'status' => 'unavailable',
                'label' => 'Fare expired',
                'expires_at' => $expiresAt,
                'found_at' => null,
            ];
        }

        $label = 'Available for this date';

        if ($expiresAt && strtotime($expiresAt) !== false && strtotime($expiresAt) > time()) {
            $label = 'Available — book soon';
        }

        return [
            'status' => 'available',
            'label' => $label,
            'expires_at' => $expiresAt,
            'found_at' => null,
        ];
    }

    public static function parseIsoDuration(?string $duration): int
    {
        if (! $duration) {
            return 0;
        }

        preg_match('/(\d+)H/i', $duration, $hours);
        preg_match('/(\d+)M/i', $duration, $minutes);

        return ((int) ($hours[1] ?? 0) * 60) + (int) ($minutes[1] ?? 0);
    }

    public static function applyMarkup(float $supplierTotal): float
    {
        if ($supplierTotal <= 0) {
            return 0;
        }

        $percent = (float) config('flights.markup.percent', 0);
        $fixed = (float) config('flights.markup.fixed', 0);

        return round($supplierTotal * (1 + $percent / 100) + $fixed, 2);
    }

    /**
     * @return array{
     *     supplier_total: float,
     *     base_amount: float,
     *     tax_amount: float,
     *     markup: float,
     *     price: float,
     *     currency: string
     * }
     */
    public static function resolvePricing(array $offer): array
    {
        $supplierTotal = (float) ($offer['total_amount'] ?? 0);
        $taxAmount = (float) ($offer['tax_amount'] ?? 0);
        $baseAmount = (float) ($offer['base_amount'] ?? max(0, $supplierTotal - $taxAmount));
        $sellingTotal = self::applyMarkup($supplierTotal);

        return [
            'supplier_total' => $supplierTotal,
            'base_amount' => $baseAmount,
            'tax_amount' => $taxAmount,
            'markup' => round(max(0, $sellingTotal - $supplierTotal), 2),
            'price' => $sellingTotal,
            'currency' => strtoupper($offer['total_currency'] ?? 'USD'),
        ];
    }

    public static function formatPrice(float $amount, int $decimals = 2): string
    {
        return number_format($amount, $decimals);
    }

    public static function mapDuffelOfferToArray(array $offer): array
    {
        $slice = $offer['slices'][0] ?? [];
        $segments = $slice['segments'] ?? [];
        $firstSegment = $segments[0] ?? [];
        $lastSegment = $segments[array_key_last($segments)] ?? $firstSegment;

        $airlineCode = strtoupper($firstSegment['marketing_carrier']['iata_code'] ?? 'XX');
        $operatingCarrier = $firstSegment['operating_carrier']['name'] ?? null;
        $marketingCarrier = $firstSegment['marketing_carrier']['name'] ?? self::airlineName($airlineCode);

        $pricing = self::resolvePricing($offer);

        return [
            'id' => $offer['id'] ?? null,
            'flight_number' => trim($airlineCode . ($firstSegment['marketing_carrier_flight_number'] ?? '')),
            'airline' => $operatingCarrier ?: $marketingCarrier,
            'marketing_airline' => $marketingCarrier,
            'operating_airline' => $operatingCarrier,
            'airline_code' => $airlineCode,
            'airline_logo' => $firstSegment['marketing_carrier']['logo_symbol_url']
                ?? $firstSegment['operating_carrier']['logo_symbol_url']
                ?? self::airlineLogoUrl($airlineCode),
            'owner_name' => $offer['owner']['name'] ?? $marketingCarrier,
            'departure' => self::segment(
                $firstSegment['origin']['iata_code'] ?? '',
                $firstSegment['departing_at'] ?? ''
            ),
            'arrival' => self::segment(
                $lastSegment['destination']['iata_code'] ?? '',
                $lastSegment['arriving_at'] ?? ''
            ),
            'duration' => self::formatMinutes(self::parseIsoDuration($slice['duration'] ?? null)),
            'stops' => max(0, count($segments) - 1),
            'cabin_class' => strtoupper($slice['segments'][0]['passengers'][0]['cabin_class'] ?? 'economy'),
            'supplier_total' => $pricing['supplier_total'],
            'base_amount' => $pricing['base_amount'],
            'tax_amount' => $pricing['tax_amount'],
            'markup' => $pricing['markup'],
            'price' => $pricing['price'],
            'currency' => $pricing['currency'],
            'baggage' => self::resolveBaggageLabel($offer),
            'refundable' => self::resolveRefundableLabel($offer),
            'affiliate_name' => 'Duffel',
            'affiliate_url' => route('flights.checkout', ['offerId' => $offer['id'] ?? '']),
            'availability_label' => self::resolveDuffelAvailability($offer)['label'],
            'price_expires_at' => $offer['expires_at'] ?? null,
            'offer_data' => $offer,
        ];
    }

    public static function resolveBaggageLabel(array $offer): string
    {
        foreach ($offer['passengers'] ?? [] as $passenger) {
            foreach ($passenger['baggages'] ?? [] as $bag) {
                $quantity = (int) ($bag['quantity'] ?? 0);
                if ($quantity > 0) {
                    return $quantity . ' ' . str_replace('_', ' ', $bag['type'] ?? 'bag') . '(s)';
                }
            }
        }

        return 'Check fare details';
    }

    public static function resolveRefundableLabel(array $offer): string
    {
        $refund = $offer['conditions']['refund_before_departure'] ?? null;

        if (is_array($refund)) {
            return ($refund['allowed'] ?? false) ? 'Refundable' : 'Non-refundable';
        }

        return 'Varies by fare';
    }
}
