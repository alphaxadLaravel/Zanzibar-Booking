<?php

namespace App\Support;

use Carbon\Carbon;

class HotelbedsExtrasMapper
{
    /**
     * @param  array<int, array<string, mixed>>  $policies
     * @return array<int, string>
     */
    public static function formatCancellationPolicies(array $policies, string $currency = 'USD'): array
    {
        $lines = [];

        foreach ($policies as $policy) {
            if (! is_array($policy)) {
                continue;
            }

            $amount = $policy['amount'] ?? $policy['hotelAmount'] ?? null;
            $from = $policy['from'] ?? null;

            if ($from === null || $amount === null) {
                continue;
            }

            try {
                $fromLabel = Carbon::parse($from)->format('M j, Y g:i A');
            } catch (\Throwable) {
                $fromLabel = (string) $from;
            }

            $lines[] = sprintf(
                'From %s: cancellation fee %s %s',
                $fromLabel,
                strtoupper($currency),
                number_format((float) $amount, 2)
            );
        }

        return $lines;
    }

    public static function reviewSourceLabel(?string $type): string
    {
        return match (strtoupper(trim((string) $type))) {
            'TRIPADVISOR' => 'TripAdvisor',
            'HOTELBEDS' => 'Hotelbeds',
            default => $type !== null && $type !== '' ? $type : 'Guest rating',
        };
    }
}
