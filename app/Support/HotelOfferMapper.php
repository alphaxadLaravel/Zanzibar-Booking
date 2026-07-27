<?php

namespace App\Support;

use App\DTOs\HotelSearchCriteria;

class HotelOfferMapper
{
    /**
     * @return array{supplier_total: float, markup: float, price: float}
     */
    public static function applyMarkup(float $supplierTotal): array
    {
        $percent = (float) config('hotels.markup.percent', 0);
        $fixed = (float) config('hotels.markup.fixed', 0);
        $price = round($supplierTotal * (1 + ($percent / 100)) + $fixed, 2);
        $markup = round(max(0, $price - $supplierTotal), 2);

        return [
            'supplier_total' => round($supplierTotal, 2),
            'markup' => $markup,
            'price' => $price,
        ];
    }

    /**
     * @param  array<string, mixed>  $hotel
     * @param  array<string, mixed>  $rate
     * @return array<string, mixed>
     */
    public static function mapRateToArray(
        array $hotel,
        array $rate,
        HotelSearchCriteria $criteria,
        array $destinationMeta,
    ): array {
        $supplierTotal = (float) ($rate['net'] ?? $rate['sellingRate'] ?? 0);
        $pricing = self::applyMarkup($supplierTotal);
        $rateKey = (string) ($rate['rateKey'] ?? '');

        return [
            'id' => md5($rateKey),
            'rate_key' => $rateKey,
            'hotel_code' => (string) ($hotel['code'] ?? ''),
            'hotel_name' => (string) ($hotel['name'] ?? 'Hotel'),
            'destination_code' => (string) ($destinationMeta['code'] ?? $criteria->destination),
            'destination_name' => (string) ($destinationMeta['name'] ?? $criteria->destination),
            'check_in' => $criteria->checkIn,
            'check_out' => $criteria->checkOut,
            'room_name' => (string) ($rate['roomName'] ?? $rate['name'] ?? 'Room'),
            'board_name' => (string) ($rate['boardName'] ?? $rate['boardCode'] ?? ''),
            'category_code' => isset($hotel['categoryCode']) ? (string) $hotel['categoryCode'] : null,
            'supplier_total' => $pricing['supplier_total'],
            'markup' => $pricing['markup'],
            'price' => $pricing['price'],
            'currency' => strtoupper((string) ($rate['currency'] ?? $criteria->currency)),
            'rate_type' => strtoupper((string) ($rate['rateType'] ?? 'BOOKABLE')),
            'rooms' => $criteria->rooms,
            'adults' => $criteria->adults,
            'children' => $criteria->children,
            'cancellation_policies' => $rate['cancellationPolicies'] ?? [],
            'latitude' => isset($hotel['latitude']) ? (string) $hotel['latitude'] : null,
            'longitude' => isset($hotel['longitude']) ? (string) $hotel['longitude'] : null,
        ];
    }

    public static function categoryStars(?string $categoryCode): ?int
    {
        if (! $categoryCode) {
            return null;
        }

        if (preg_match('/(\d)/', $categoryCode, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }
}
