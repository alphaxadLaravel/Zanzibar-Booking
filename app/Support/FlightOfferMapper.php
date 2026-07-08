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
     * Derive bookability from TravelPayouts offer data.
     * Note: this API does not expose seat counts or sold-out inventory.
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
}
