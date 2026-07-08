<?php

namespace App\Support;

class DuffelOrderBuilder
{
    /**
     * Map checkout form passengers onto Duffel offer passenger IDs for POST /air/orders.
     *
     * @param  array<int, array<string, mixed>>  $offerPassengers  From refreshed offer.passengers
     * @param  array<int, array<string, mixed>>  $formPassengers   From booking form
     * @return array<int, array<string, mixed>>
     */
    public static function passengers(
        array $offerPassengers,
        array $formPassengers,
        string $contactEmail,
        string $contactPhone,
    ): array {
        $slots = self::passengerSlots($offerPassengers);
        $groups = self::groupFormPassengers($formPassengers);

        if (count($groups['adult']) > count($slots['adult'])
            || count($groups['child']) > count($slots['child'])
            || count($groups['infant']) > count($slots['infant'])) {
            throw new \InvalidArgumentException('Passenger count does not match the selected offer. Please search again.');
        }

        $payload = [];
        $adultOfferIds = [];

        foreach ($groups['adult'] as $index => $passenger) {
            $slot = $slots['adult'][$index];
            $adultOfferIds[] = $slot['id'];
            $payload[] = self::passengerPayload($slot['id'], $passenger, $contactEmail, $contactPhone);
        }

        foreach ($groups['child'] as $index => $passenger) {
            $slot = $slots['child'][$index];
            $payload[] = self::passengerPayload($slot['id'], $passenger, $contactEmail, $contactPhone);
        }

        foreach ($groups['infant'] as $index => $passenger) {
            $slot = $slots['infant'][$index];
            $responsibleAdultId = $adultOfferIds[$index] ?? $adultOfferIds[0] ?? null;

            if (! $responsibleAdultId) {
                throw new \InvalidArgumentException('Each infant must travel with an adult passenger.');
            }

            $entry = self::passengerPayload($slot['id'], $passenger, $contactEmail, $contactPhone);
            $entry['infant_passenger_id'] = $responsibleAdultId;
            $payload[] = $entry;
        }

        return $payload;
    }

    /**
     * @param  array<int, array<string, mixed>>  $offerPassengers
     * @return array{adult: array<int, array<string, mixed>>, child: array<int, array<string, mixed>>, infant: array<int, array<string, mixed>>}
     */
    protected static function passengerSlots(array $offerPassengers): array
    {
        $slots = ['adult' => [], 'child' => [], 'infant' => []];

        foreach ($offerPassengers as $passenger) {
            $type = strtolower((string) ($passenger['type'] ?? 'adult'));

            if (str_contains($type, 'infant')) {
                $slots['infant'][] = $passenger;

                continue;
            }

            if ($type === 'child') {
                $slots['child'][] = $passenger;

                continue;
            }

            $slots['adult'][] = $passenger;
        }

        return $slots;
    }

    /**
     * @param  array<int, array<string, mixed>>  $formPassengers
     * @return array{adult: array<int, array<string, mixed>>, child: array<int, array<string, mixed>>, infant: array<int, array<string, mixed>>}
     */
    protected static function groupFormPassengers(array $formPassengers): array
    {
        $groups = ['adult' => [], 'child' => [], 'infant' => []];

        foreach ($formPassengers as $passenger) {
            $type = strtolower((string) ($passenger['type'] ?? 'adult'));
            $groups[$type === 'infant' ? 'infant' : ($type === 'child' ? 'child' : 'adult')][] = $passenger;
        }

        return $groups;
    }

    /**
     * @param  array<string, mixed>  $passenger
     * @return array<string, mixed>
     */
    protected static function passengerPayload(
        string $offerPassengerId,
        array $passenger,
        string $contactEmail,
        string $contactPhone,
    ): array {
        $gender = strtolower((string) ($passenger['gender'] ?? 'm'));
        $gender = in_array($gender, ['m', 'f'], true) ? $gender : 'm';

        return [
            'id' => $offerPassengerId,
            'given_name' => trim((string) $passenger['first_name']),
            'family_name' => trim((string) $passenger['last_name']),
            'born_on' => date('Y-m-d', strtotime((string) $passenger['date_of_birth'])),
            'gender' => $gender,
            'title' => $gender === 'f' ? 'mrs' : 'mr',
            'email' => $contactEmail,
            'phone_number' => self::normalizePhone($contactPhone),
        ];
    }

    protected static function normalizePhone(string $phone): string
    {
        $phone = trim($phone);

        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        $digits = preg_replace('/\D+/', '', $phone) ?? $phone;

        if (str_starts_with($digits, '255')) {
            return '+' . $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '+255' . substr($digits, 1);
        }

        return '+' . $digits;
    }
}
