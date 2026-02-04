<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyConverter
{
    protected static ?array $rates = null;

    /**
     * Get rates from config (and optionally API). Uses cache when API is enabled.
     */
    public static function getRates(): array
    {
        if (self::$rates !== null) {
            return self::$rates;
        }

        $config = config('currency_rates', []);
        $useApi = env('EXCHANGE_RATES_USE_API', false);
        $cacheKey = $config['cache_key'] ?? 'currency_rates_usd';
        $cacheTtl = ($config['cache_ttl_minutes'] ?? 1440) * 60;

        if ($useApi) {
            self::$rates = Cache::remember($cacheKey, $cacheTtl, function () use ($config) {
                $fetched = self::fetchRatesFromApi();
                return $fetched !== null ? $fetched : ($config['rates'] ?? ['USD' => 1.0]);
            });
        } else {
            self::$rates = $config['rates'] ?? ['USD' => 1.0];
        }

        return self::$rates;
    }

    /**
     * Fetch latest rates from Frankfurter (free, no key).
     */
    protected static function fetchRatesFromApi(): ?array
    {
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout(5)->get('https://api.frankfurter.dev/latest', [
                'from' => config('currency_rates.base', 'USD'),
                'to'   => implode(',', array_keys(config('currency_rates.rates', []))),
            ]);
            if ($response->successful()) {
                $data = $response->json();
                $rates = $data['rates'] ?? [];
                $rates['USD'] = 1.0;
                return $rates;
            }
        } catch (\Throwable $e) {
            report($e);
        }
        return null;
    }

    /**
     * Convert amount from one currency to another.
     *
     * @param  float|int  $amount  Amount in $fromCurrency
     * @param  string  $fromCurrency  e.g. 'USD'
     * @param  string  $toCurrency  e.g. 'TZS'
     * @return float Converted amount
     */
    public static function convert(float|int $amount, string $fromCurrency, string $toCurrency): float
    {
        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        if ($fromCurrency === $toCurrency) {
            return (float) $amount;
        }

        $rates = self::getRates();
        $fromRate = $rates[$fromCurrency] ?? null;
        $toRate = $rates[$toCurrency] ?? null;

        if ($fromRate === null || $toRate === null) {
            return (float) $amount;
        }

        // amount is in $fromCurrency; convert to USD then to $toCurrency
        $usd = $amount / $fromRate;
        return round($usd * $toRate, 2);
    }

    /**
     * Convert from base currency (USD) to target currency.
     */
    public static function convertFromBase(float|int $amountUsd, string $toCurrency): float
    {
        return self::convert($amountUsd, config('currency_rates.base', 'USD'), $toCurrency);
    }
}
