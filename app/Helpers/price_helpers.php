<?php

use App\Services\PriceForUserService;

if (! function_exists('priceForUser')) {
    /**
     * Convert USD amount to user's currency and return formatted string or array.
     *
     * @param  float|int  $amountUsd  Price in USD
     * @param  int  $decimals  Decimal places
     * @param  bool  $returnArray  If true, return full array; otherwise formatted string
     * @return string|array{amount: float, currency: string, formatted: string, symbol: string}
     */
    function priceForUser(float|int $amountUsd, int $decimals = 2, bool $returnArray = false): string|array
    {
        $result = PriceForUserService::convert($amountUsd, $decimals);
        return $returnArray ? $result : $result['formatted'];
    }
}

if (! function_exists('userCurrency')) {
    /**
     * Get the currency code for the current user (e.g. TZS, USD).
     */
    function userCurrency(): string
    {
        return PriceForUserService::getUserCurrency();
    }
}

if (! function_exists('userCountryCode')) {
    /**
     * Get the country code for the current user (e.g. TZ, US).
     */
    function userCountryCode(): ?string
    {
        return PriceForUserService::getUserCountryCode();
    }
}

if (! function_exists('userCurrencyRate')) {
    /**
     * Get the exchange rate from USD to user's currency (1 USD = rate × user currency).
     * Used for client-side price recalculation in booking widgets.
     */
    function userCurrencyRate(): float
    {
        $currency = userCurrency();
        $rates = \App\Services\CurrencyConverter::getRates();

        return (float) ($rates[$currency] ?? 1.0);
    }
}
