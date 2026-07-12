<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FlightBooking;
use App\Models\Payment;
use App\Services\CurrencyConverter;
use App\Services\FlightService;
use App\Services\Flights\DuffelApiService;
use App\Services\Flights\FlightBookingService;
use App\Support\FlightOfferMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Knox\Pesapal\Facades\Pesapal;

class FlightApiController extends Controller
{
    public function __construct(
        protected FlightService $flightService,
        protected DuffelApiService $duffelApi,
        protected FlightBookingService $flightBookingService,
    ) {}

    public function locations(Request $request)
    {
        $request->validate([
            'keyword' => ['required', 'string', 'min:2', 'max:50'],
        ]);

        $locations = $this->flightService->searchLocations(
            $request->input('keyword'),
            $request->input('countryCode'),
            $request->input('subTypes', ['AIRPORT', 'CITY']),
            (int) $request->input('limit', 10),
        );

        return response()->json(['data' => $locations]);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'tripType' => ['required', 'in:one_way,round_trip,multi_city'],
            'origin' => ['required', 'string', 'size:3'],
            'destination' => ['required', 'string', 'size:3', 'different:origin'],
            'departureDate' => ['required', 'date', 'after_or_equal:today'],
            'returnDate' => ['nullable', 'date', 'after_or_equal:departureDate', 'required_if:tripType,round_trip'],
            'adults' => ['required', 'integer', 'min:1', 'max:9'],
            'children' => ['nullable', 'integer', 'min:0', 'max:9'],
            'infants' => ['nullable', 'integer', 'min:0', 'max:9'],
            'travelClass' => ['required', 'in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST'],
            'nonStop' => ['nullable', 'boolean'],
            'filterAirline' => ['nullable', 'string'],
            'sortBy' => ['nullable', 'in:price_asc,price_desc,duration'],
        ]);

        $validated['origin'] = strtoupper($validated['origin']);
        $validated['destination'] = strtoupper($validated['destination']);
        $validated['children'] = (int) ($validated['children'] ?? 0);
        $validated['infants'] = (int) ($validated['infants'] ?? 0);

        try {
            $result = $this->flightService->searchFlights($validated);
            $offers = $result['offers'] ?? $result['flights'] ?? $result;

            if (is_array($offers) && isset($validated['filterAirline'])) {
                $airline = strtoupper($validated['filterAirline']);
                $offers = array_values(array_filter($offers, function ($offer) use ($airline) {
                    $code = strtoupper($offer['airline'] ?? $offer['airline_code'] ?? '');
                    return str_contains($code, $airline);
                }));
            }

            $sortBy = $validated['sortBy'] ?? 'price_asc';
            if (is_array($offers)) {
                usort($offers, function ($a, $b) use ($sortBy) {
                    $pa = (float) ($a['price'] ?? $a['total_amount'] ?? 0);
                    $pb = (float) ($b['price'] ?? $b['total_amount'] ?? 0);
                    return $sortBy === 'price_desc' ? $pb <=> $pa : $pa <=> $pb;
                });
            }

            return response()->json([
                'data' => $offers,
                'meta' => [
                    'count' => is_array($offers) ? count($offers) : 0,
                    'search_id' => $result['search_id'] ?? null,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('API flight search failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'offer_id' => ['required', 'string', 'max:100'],
        ]);

        try {
            $offer = $this->duffelApi->getOffer($validated['offer_id']);
            $flight = FlightOfferMapper::mapDuffelOfferToArray($offer);

            return response()->json([
                'offer_id' => $validated['offer_id'],
                'flight' => $flight,
                'offer' => $offer,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function book(Request $request)
    {
        $validated = $request->validate([
            'flight_id' => ['required', 'string', 'max:100'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'adults' => ['required', 'integer', 'min:1', 'max:9'],
            'children' => ['nullable', 'integer', 'min:0', 'max:9'],
            'infants' => ['nullable', 'integer', 'min:0', 'max:9'],
            'passengers' => ['required', 'array', 'min:1'],
            'passengers.*.type' => ['required', 'in:adult,child,infant'],
            'passengers.*.first_name' => ['required', 'string', 'max:100'],
            'passengers.*.last_name' => ['required', 'string', 'max:100'],
            'passengers.*.date_of_birth' => ['required', 'date', 'before:today'],
            'passengers.*.gender' => ['required', 'in:M,F,m,f'],
        ]);

        try {
            $offer = $this->duffelApi->getOffer($validated['flight_id']);
            $booking = $this->flightBookingService->createPendingBooking($offer, $validated);

            return response()->json([
                'message' => 'Passenger details saved. Complete payment to confirm your flight.',
                'booking' => $this->transformFlightBooking($booking->load('passengers')),
            ], 201);
        } catch (\Throwable $e) {
            Log::error('API flight book failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function pay(Request $request, string $bookingReference)
    {
        try {
            $booking = FlightBooking::where('booking_reference', $bookingReference)
                ->where(function ($q) use ($request) {
                    $q->where('user_id', $request->user()->id)
                        ->orWhereRaw('LOWER(contact_email) = ?', [strtolower($request->user()->email)]);
                })
                ->firstOrFail();

            if ($booking->status === 'confirmed') {
                return response()->json([
                    'message' => 'Flight already confirmed',
                    'booking' => $this->transformFlightBooking($booking->load('passengers')),
                ]);
            }

            $this->configurePesapal();

            $payment = Payment::create([
                'flight_booking_id' => $booking->id,
                'amount' => $booking->total_price,
                'status' => 'PENDING',
                'payment_method' => 'PESAPAL',
                'transactionid' => Pesapal::random_reference(),
                'user_id' => $booking->user_id ?? $request->user()->id,
            ]);

            $booking->update(['payment_id' => $payment->id]);

            $userCurrency = function_exists('userCurrency') ? userCurrency() : 'USD';
            $amountUsd = (float) $booking->total_price;
            if ($userCurrency !== 'USD' && $booking->currency === 'USD') {
                $amountToSend = round(CurrencyConverter::convertFromBase($amountUsd, $userCurrency), 2);
                $currencyToSend = $userCurrency;
            } else {
                $amountToSend = $amountUsd;
                $currencyToSend = $booking->currency ?? 'USD';
            }

            $name = $booking->passengers()->first();
            $details = [
                'amount' => $amountToSend,
                'description' => 'Flight ' . $booking->origin_code . ' to ' . $booking->destination_code,
                'type' => 'MERCHANT',
                'first_name' => $name?->first_name ?? 'Guest',
                'last_name' => $name?->last_name ?? 'Traveler',
                'email' => $booking->contact_email,
                'phonenumber' => $booking->contact_phone,
                'reference' => $payment->transactionid,
                'currency' => $currencyToSend,
                'callback_url' => url('/api/v1/payments/mobile-callback'),
                'notification_url' => url('/payment/confirmation'),
            ];

            $iframe = Pesapal::makePayment($details);
            if (empty($iframe)) {
                throw new \RuntimeException('Unable to load the payment form.');
            }

            $wrapped = PaymentApiController::wrapIframeHtml($iframe);

            return response()->json([
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transactionid,
                'iframe_html' => $wrapped,
                'callback_scheme' => PaymentApiController::DEEP_LINK_SCHEME,
                'booking' => $this->transformFlightBooking($booking->load('passengers')),
            ]);
        } catch (\Throwable $e) {
            Log::error('API flight pay failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function myBookings(Request $request)
    {
        $bookings = FlightBooking::query()
            ->with('passengers')
            ->where(function ($query) use ($request) {
                $query->where('user_id', $request->user()->id)
                    ->orWhereRaw('LOWER(contact_email) = ?', [strtolower($request->user()->email)]);
            })
            ->latest()
            ->paginate(12);

        return response()->json([
            'data' => collect($bookings->items())->map(fn ($b) => $this->transformFlightBooking($b)),
            'meta' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'total' => $bookings->total(),
            ],
        ]);
    }

    public function show(Request $request, string $bookingReference)
    {
        $booking = FlightBooking::with('passengers')
            ->where('booking_reference', $bookingReference)
            ->where(function ($query) use ($request) {
                $query->where('user_id', $request->user()->id)
                    ->orWhereRaw('LOWER(contact_email) = ?', [strtolower($request->user()->email)]);
            })
            ->firstOrFail();

        return response()->json(['data' => $this->transformFlightBooking($booking)]);
    }

    protected function transformFlightBooking(FlightBooking $booking): array
    {
        return [
            'id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'status' => $booking->status,
            'origin_code' => $booking->origin_code,
            'destination_code' => $booking->destination_code,
            'airline_name' => $booking->airline_name,
            'departure_at' => $booking->departure_at,
            'arrival_at' => $booking->arrival_at,
            'total_price' => (float) $booking->total_price,
            'currency' => $booking->currency,
            'contact_email' => $booking->contact_email,
            'contact_phone' => $booking->contact_phone,
            'passengers' => $booking->relationLoaded('passengers')
                ? $booking->passengers->map(fn ($p) => [
                    'type' => $p->type,
                    'first_name' => $p->first_name,
                    'last_name' => $p->last_name,
                    'date_of_birth' => $p->date_of_birth,
                    'gender' => $p->gender,
                ])->values()
                : [],
            'flight' => FlightOfferMapper::mapDuffelOfferToArray($booking->flight_offer ?? []),
        ];
    }

    protected function configurePesapal(): void
    {
        $consumerKey = trim(config('pesapal.consumer_key', ''), " \t\n\r\0\x0B\"'");
        $consumerSecret = trim(config('pesapal.consumer_secret', ''), " \t\n\r\0\x0B\"'");
        $environment = config('pesapal.environment', 'sandbox');

        if (empty($consumerKey) || empty($consumerSecret)) {
            throw new \RuntimeException('Payment gateway is not configured.');
        }

        config([
            'pesapal.consumer_key' => $consumerKey,
            'pesapal.consumer_secret' => $consumerSecret,
            'pesapal.callback_route' => 'payment.success',
            'pesapal.environment' => $environment,
            'pesapal.live' => $environment === 'live',
        ]);
    }
}
