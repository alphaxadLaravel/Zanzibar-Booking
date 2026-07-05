<?php

namespace App\Support;

class DealPermissions
{
    public static function types(): array
    {
        return config('deal_permissions.types', []);
    }

    public static function slug(string $type, string $action): string
    {
        $prefix = self::types()[$type]['prefix'] ?? $type;

        return "{$prefix}.{$action}";
    }

    public static function slugsForAction(string $action): array
    {
        return collect(self::types())
            ->map(fn (array $meta, string $type) => self::slug($type, $action))
            ->values()
            ->all();
    }

    public static function viewSlugs(): array
    {
        return self::slugsForAction('view');
    }

    public static function normalizeType(?string $type): ?string
    {
        if (!$type) {
            return null;
        }

        $type = strtolower($type);

        return array_key_exists($type, self::types()) ? $type : null;
    }
}
