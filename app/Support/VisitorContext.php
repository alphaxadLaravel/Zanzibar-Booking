<?php

namespace App\Support;

use App\Services\PriceForUserService;

class VisitorContext
{
    public static function capture(): array
    {
        $userAgent = request()->userAgent() ?? '';

        return [
            'session_id' => session()->getId(),
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'country' => self::country(),
            'device' => self::deviceType($userAgent),
            'browser' => self::browser($userAgent),
            'operating_system' => self::os($userAgent),
        ];
    }

    public static function country(): ?string
    {
        try {
            return PriceForUserService::getUserCountryCode();
        } catch (\Throwable) {
            return null;
        }
    }

    public static function deviceType(string $userAgent): string
    {
        if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            return 'mobile';
        }

        if (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            return 'tablet';
        }

        return 'desktop';
    }

    public static function browser(string $userAgent): string
    {
        $patterns = [
            'Edge' => '/Edg\//i',
            'Chrome' => '/Chrome\//i',
            'Firefox' => '/Firefox\//i',
            'Safari' => '/Safari\//i',
            'Opera' => '/OPR\//i',
            'IE' => '/MSIE|Trident/i',
        ];

        foreach ($patterns as $name => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return $name;
            }
        }

        return 'Unknown';
    }

    public static function os(string $userAgent): string
    {
        $patterns = [
            'Windows' => '/Windows NT/i',
            'macOS' => '/Macintosh|Mac OS X/i',
            'Linux' => '/Linux/i',
            'Android' => '/Android/i',
            'iOS' => '/iPhone|iPad|iPod/i',
        ];

        foreach ($patterns as $name => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return $name;
            }
        }

        return 'Unknown';
    }
}
