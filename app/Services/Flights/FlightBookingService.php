<?php

namespace App\Services\Flights;

use App\Models\FlightBooking;
use App\Models\FlightPassenger;
use App\Support\FlightOfferMapper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FlightBookingService
{
    public function __construct(
        protected DuffelApiService $duffelApi,
    ) {}

    /**
     * @param  array<string, mixed>  $offer
     * @param  array<string, mixed>  $validated
     */
    public function createPendingBooking(array $offer, array $validated): FlightBooking
    {
        $mapped = FlightOfferMapper::mapDuffelOfferToArray($offer);

        return DB::transaction(function () use ($offer, $validated, $mapped) {
            $booking = FlightBooking::create([
                'booking_reference' => FlightBooking::generateBookingReference(),
                'user_id' => auth()->id(),
                'flight_number' => $mapped['flight_number'],
                'airline_code' => $mapped['airline_code'],
                'airline_name' => $mapped['airline'],
                'origin_code' => $mapped['departure']['airport'],
                'origin_name' => $mapped['departure']['city'],
                'destination_code' => $mapped['arrival']['airport'],
                'destination_name' => $mapped['arrival']['city'],
                'departure_datetime' => $mapped['departure']['datetime'],
                'arrival_datetime' => $mapped['arrival']['datetime'],
                'duration' => $mapped['duration'],
                'stops' => $mapped['stops'],
                'status' => 'pending',
                'travel_class' => strtoupper(session('flight_search_criteria.travelClass', $mapped['cabin_class'] ?? 'ECONOMY')),
                'adults' => $validated['adults'],
                'children' => (int) ($validated['children'] ?? 0),
                'infants' => (int) ($validated['infants'] ?? 0),
                'base_price' => $mapped['base_amount'],
                'taxes' => $mapped['tax_amount'],
                'total_price' => $mapped['price'],
                'currency' => $mapped['currency'],
                'flight_offer' => array_merge($offer, [
                    '_pricing' => [
                        'supplier_total' => $mapped['supplier_total'],
                        'markup' => $mapped['markup'],
                        'customer_total' => $mapped['price'],
                    ],
                    '_checkout' => [
                        'passengers' => $validated['passengers'],
                        'contact_email' => $validated['contact_email'],
                        'contact_phone' => $validated['contact_phone'],
                    ],
                ]),
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
            ]);

            foreach ($validated['passengers'] as $passenger) {
                FlightPassenger::create([
                    'flight_booking_id' => $booking->id,
                    'type' => $passenger['type'],
                    'first_name' => $passenger['first_name'],
                    'last_name' => $passenger['last_name'],
                    'date_of_birth' => $passenger['date_of_birth'],
                    'gender' => strtoupper($passenger['gender']),
                    'passport_number' => $passenger['passport_number'] ?? null,
                    'nationality' => $passenger['nationality'] ?? null,
                    'email' => $validated['contact_email'],
                    'phone' => $validated['contact_phone'],
                ]);
            }

            return $booking;
        });
    }

    /**
     * After Pesapal payment, refresh offer and create the Duffel order.
     */
    public function fulfillAfterPayment(FlightBooking $booking): FlightBooking
    {
        if ($booking->status === 'confirmed' && $booking->amadeus_order_id) {
            return $booking;
        }

        $offerData = $booking->flight_offer ?? [];
        $checkout = $offerData['_checkout'] ?? null;
        $offerId = $offerData['id'] ?? null;

        if (! $checkout || ! $offerId) {
            throw new \RuntimeException('Flight booking data is incomplete. Please contact support.');
        }

        $offer = $this->duffelApi->getOffer($offerId);

        $duffelOrder = $this->duffelApi->createOrder(
            $offer,
            $checkout['passengers'],
            $checkout['contact_email'],
            $checkout['contact_phone'],
        );

        $booking->update([
            'status' => 'confirmed',
            'total_price' => $duffelOrder['total_amount'] ?? $booking->total_price,
            'currency' => strtoupper($duffelOrder['total_currency'] ?? $booking->currency),
            'flight_offer' => $offer,
            'amadeus_order_id' => $duffelOrder['id'] ?? null,
            'amadeus_response' => $duffelOrder,
            'confirmed_at' => now(),
        ]);

        Log::info('Duffel flight order created after payment', [
            'flight_booking_id' => $booking->id,
            'duffel_order_id' => $duffelOrder['id'] ?? null,
        ]);

        return $booking->fresh();
    }
}
