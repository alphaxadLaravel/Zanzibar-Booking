<?php

namespace App\Support;

use Hashids\Hashids;

class HashidsHelper
{
    public static function make(): Hashids
    {
        return new Hashids('MchungajiZanzibarBookings', 10);
    }

    public static function encode(int $id): string
    {
        return self::make()->encode($id);
    }

    public static function decode(string $hash): ?int
    {
        $decoded = self::make()->decode($hash);

        return $decoded[0] ?? null;
    }

    /**
     * Accept numeric id or hashid string.
     */
    public static function resolveId(string|int $value): ?int
    {
        if (is_numeric($value)) {
            return (int) $value;
        }

        return self::decode((string) $value);
    }
}
