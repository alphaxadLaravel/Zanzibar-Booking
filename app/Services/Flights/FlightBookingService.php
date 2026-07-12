<?php

namespace App\Services\Flights;

use App\Mail\FlightTicketConfirmation;
use App\Models\FlightBooking;
use App\Models\FlightPassenger;
use App\Support\FlightOfferMapper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
                'supplier_cost' => $mapped['supplier_total'],
                'markup_amount' => $mapped['markup'],
                'total_price' => $mapped['price'],
                'currency' => $mapped['currency'],
                'flight_offer' => array_merge($offer, [
                    '_pricing' => [
                        'supplier_total' => $mapped['supplier_total'],
                        'markup' => $mapped['markup'],
                        'customer_total' => $mapped['price'],
                        'base_amount' => $mapped['base_amount'],
                        'tax_amount' => $mapped['tax_amount'],
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
     * After Pesapal payment, pay Duffel the supplier fare and confirm the ticket.
     * Customer total (with markup) stays as charged via Pesapal.
     */
    public function fulfillAfterPayment(FlightBooking $booking): FlightBooking
    {
        if ($booking->status === 'confirmed' && $booking->amadeus_order_id) {
            return $booking->loadMissing('passengers');
        }

        $offerData = $booking->flight_offer ?? [];
        $checkout = $offerData['_checkout'] ?? null;
        $pricing = $offerData['_pricing'] ?? null;
        $offerId = $offerData['id'] ?? null;

        if (! $checkout || ! $offerId) {
            throw new \RuntimeException('Flight booking data is incomplete. Please contact support.');
        }

        $offer = $this->duffelApi->getOffer($offerId);
        $supplierCost = (float) ($offer['total_amount'] ?? $booking->supplier_cost ?? 0);
        $customerTotal = (float) $booking->total_price;

        $duffelOrder = $this->duffelApi->createOrder(
            $offer,
            $checkout['passengers'],
            $checkout['contact_email'],
            $checkout['contact_phone'],
        );

        // Refresh order so booking_reference / documents are present when available.
        if (! empty($duffelOrder['id'])) {
            try {
                $duffelOrder = array_merge($duffelOrder, $this->duffelApi->getOrder($duffelOrder['id']));
            } catch (\Throwable $e) {
                Log::warning('Could not refresh Duffel order after create', [
                    'order_id' => $duffelOrder['id'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $actualSupplierCost = (float) ($duffelOrder['total_amount'] ?? $supplierCost);
        $actualMarkup = round(max(0, $customerTotal - $actualSupplierCost), 2);
        $airlinePnr = $duffelOrder['booking_reference'] ?? null;

        $booking->update([
            'status' => 'confirmed',
            'total_price' => $customerTotal,
            'supplier_cost' => $actualSupplierCost,
            'markup_amount' => $actualMarkup,
            'base_price' => (float) ($offer['base_amount'] ?? $booking->base_price),
            'taxes' => (float) ($offer['tax_amount'] ?? $booking->taxes),
            'currency' => strtoupper($duffelOrder['total_currency'] ?? $booking->currency),
            'flight_offer' => array_merge($offer, [
                '_pricing' => array_merge($pricing ?? [], [
                    'supplier_total' => $actualSupplierCost,
                    'markup' => $actualMarkup,
                    'customer_total' => $customerTotal,
                    'duffel_order_total' => $actualSupplierCost,
                ]),
                '_checkout' => $checkout,
                '_ticket' => [
                    'airline_pnr' => $airlinePnr,
                    'duffel_order_id' => $duffelOrder['id'] ?? null,
                    'documents' => $duffelOrder['documents'] ?? [],
                    'issued_at' => now()->toIso8601String(),
                    'ticket_email_sent' => false,
                ],
            ]),
            'amadeus_order_id' => $duffelOrder['id'] ?? null,
            'amadeus_response' => $duffelOrder,
            'confirmed_at' => now(),
        ]);

        $booking = $booking->fresh(['passengers']);
        $this->sendTicketEmails($booking);

        Log::info('Duffel flight order created after payment', [
            'flight_booking_id' => $booking->id,
            'duffel_order_id' => $duffelOrder['id'] ?? null,
            'airline_pnr' => $airlinePnr,
            'customer_paid' => $customerTotal,
            'duffel_paid' => $actualSupplierCost,
            'zanzibar_margin' => $actualMarkup,
        ]);

        return $booking;
    }

    public function sendTicketEmails(FlightBooking $booking): void
    {
        $booking->loadMissing('passengers');
        $ticket = $booking->ticketDetails();
        $offer = $booking->flight_offer ?? [];

        if (! empty($offer['_ticket']['ticket_email_sent'])) {
            return;
        }

        try {
            Mail::to($booking->contact_email)->send(new FlightTicketConfirmation($booking, $ticket, false));

            $adminEmail = env('ADMIN_EMAIL', 'sales-reservations@zanzibarbookings.com');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new FlightTicketConfirmation($booking, $ticket, true));
            }

            $offer['_ticket'] = array_merge($offer['_ticket'] ?? [], [
                'ticket_email_sent' => true,
                'ticket_email_sent_at' => now()->toIso8601String(),
            ]);
            $booking->update(['flight_offer' => $offer]);
        } catch (\Throwable $e) {
            Log::error('Failed to send flight ticket emails', [
                'flight_booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
