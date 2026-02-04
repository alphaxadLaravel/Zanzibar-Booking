<?php

namespace App\Services;

class PriceForUserService
{
    protected static ?string $userCountryCode = null;
    protected static ?string $userCurrency = null;

    /**
     * Detect user country (from GeoGenius) and resolve currency.
     */
    public static function getUserCurrency(): string
    {
        if (self::$userCurrency !== null) {
            return self::$userCurrency;
        }
        $countryCode = self::getUserCountryCode();
        self::$userCurrency = CountryCurrency::get($countryCode);
        return self::$userCurrency;
    }

    /**
     * Get user country code (e.g. TZ, US) via GeoGenius.
     */
    public static function getUserCountryCode(): ?string
    {
        if (self::$userCountryCode !== null) {
            return self::$userCountryCode;
        }
        // In local/CLI environments, avoid external HTTP calls and default to base currency (USD)
        if (app()->runningInConsole() || app()->environment('local')) {
            self::$userCountryCode = '';
            return null;
        }
        if (! function_exists('laravelGeoGenius')) {
            self::$userCountryCode = '';
            return null;
        }
        try {
            self::$userCountryCode = laravelGeoGenius()->geo()->getCountryCode();
        } catch (\Throwable $e) {
            self::$userCountryCode = '';
        }
        return self::$userCountryCode ?: null;
    }

    /**
     * Convert USD amount to user's currency and return formatted for display.
     *
     * @param  float|int  $amountUsd  Price in USD
     * @param  int  $decimals  Decimal places
     * @return array{amount: float, currency: string, formatted: string, symbol: string}
     */
    public static function convert(float|int $amountUsd, int $decimals = 2): array
    {
        $currency = self::getUserCurrency();
        $amount = CurrencyConverter::convertFromBase($amountUsd, $currency);
        $formatted = self::format($amount, $currency, $decimals);
        $symbol = self::currencySymbol($currency);

        return [
            'amount' => $amount,
            'currency' => $currency,
            'formatted' => $formatted,
            'symbol' => $symbol,
        ];
    }

    /**
     * Format amount with currency (e.g. "2,650.00 TZS" or "TZS 2,650").
     */
    public static function format(float $amount, string $currency, int $decimals = 2): string
    {
        $symbol = self::currencySymbol($currency);
        $formatted = number_format($amount, $decimals);

        return $currency === 'USD'
            ? '$' . $formatted
            : $symbol . ' ' . $formatted;
    }

    /**
     * Simple currency symbols for common currencies.
     */
    public static function currencySymbol(string $currency): string
    {
        $symbols = [
            'USD' => '$', 'TZS' => 'TZS', 'KES' => 'KES', 'UGX' => 'UGX', 'ZAR' => 'R',
            'GBP' => '£', 'EUR' => '€', 'AED' => 'AED', 'INR' => '₹', 'CNY' => '¥',
            'JPY' => '¥', 'CHF' => 'CHF', 'AUD' => 'A$', 'CAD' => 'C$', 'NGN' => '₦',
            'GHS' => 'GH₵', 'MAD' => 'MAD', 'EGP' => 'E£', 'RWF' => 'FRw', 'BIF' => 'FBu',
            'ZMW' => 'ZK', 'MZN' => 'MT', 'MWK' => 'MK', 'ETB' => 'Br', 'THB' => '฿',
            'PKR' => 'Rs', 'BDT' => '৳', 'LKR' => 'Rs', 'NPR' => 'Rs', 'PHP' => '₱',
            'IDR' => 'Rp', 'VND' => '₫', 'KRW' => '₩', 'TRY' => '₺', 'RUB' => '₽',
            'BRL' => 'R$', 'MXN' => '$', 'ARS' => '$', 'SAR' => 'SAR', 'QAR' => 'QR',
        ];
        return $symbols[$currency] ?? $currency . ' ';
    }

    /**
     * Reset cached country/currency (e.g. for testing).
     */
    public static function reset(): void
    {
        self::$userCountryCode = null;
        self::$userCurrency = null;
    }
}
