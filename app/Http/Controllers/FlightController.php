<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightAffiliateClickRequest;
use App\Models\FlightBooking;
use App\Models\Payment;
use App\Services\CurrencyConverter;
use App\Services\Flights\AffiliateTrackingService;
use App\Services\Flights\DuffelApiService;
use App\Services\Flights\FlightBookingService;
use App\Services\FlightService;
use App\Support\FlightOfferMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Knox\Pesapal\Facades\Pesapal;

class FlightController extends Controller
{
    public function __construct(
        protected FlightService $flightService,
        protected AffiliateTrackingService $tracking,
        protected DuffelApiService $duffelApi,
        protected FlightBookingService $flightBookingService,
    ) {}

    public function index()
    {
        return view('website.pages.flights');
    }

    public function searchLocations(Request $request)
    {
        $request->validate([
            'keyword' => ['required', 'string', 'min:2', 'max:50'],
        ]);

        $locations = $this->flightService->searchLocations(
            $request->input('keyword'),
            $request->input('countryCode'),
            $request->input('subTypes', ['AIRPORT', 'CITY']),
            (int) $request->input('limit', 10),
            $request->input('view', 'FULL')
        );

        return response()->json($locations);
    }

    public function affiliateRedirect(FlightAffiliateClickRequest $request)
    {
        $data = $request->validated();

        try {
            $this->tracking->logClick([
                'flight_search_id' => $data['flight_search_id'] ?? session('last_flight_search_id'),
                'airline' => $data['airline'] ?? null,
                'flight_number' => $data['flight_number'] ?? null,
                'origin' => $data['origin'],
                'destination' => $data['destination'],
                'price' => $data['price'] ?? null,
                'currency' => $data['currency'] ?? 'USD',
                'affiliate_name' => $data['affiliate_name'],
                'affiliate_url' => $data['affiliate_url'],
            ]);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->to($data['affiliate_url']);
    }

    public function checkout(string $offerId)
    {
        try {
            $offer = $this->duffelApi->getOffer($offerId);
            $flight = FlightOfferMapper::mapDuffelOfferToArray($offer);
            $searchCriteria = session('flight_search_criteria', []);

            $passengerCounts = [
                'adults' => max(1, (int) ($searchCriteria['adults'] ?? count(array_filter(
                    $offer['passengers'] ?? [],
                    fn ($p) => ($p['type'] ?? '') === 'adult'
                )) ?: 1)),
                'children' => (int) ($searchCriteria['children'] ?? 0),
                'infants' => (int) ($searchCriteria['infants'] ?? 0),
            ];

            session(['duffel_checkout_offer' => $offer]);

            return view('website.pages.flight-booking', compact('flight', 'offerId', 'offer', 'searchCriteria', 'passengerCounts'));
        } catch (\Throwable $e) {
            return redirect()
                ->route('flights.index')
                ->with('error', $e->getMessage());
        }
    }

    public function show($flightId)
    {
        return redirect()->route('flights.index')->with('error', 'Please use Book Flight on the search results page.');
    }

    public function bookingForm($flightId)
    {
        return redirect()->route('flights.checkout', ['offerId' => $flightId]);
    }

    public function processBooking(Request $request)
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
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('flights.payment', ['bookingReference' => $booking->booking_reference])
            ->with('success', 'Passenger details saved. Complete payment to confirm your flight.');
    }

    public function payment(string $bookingReference)
    {
        try {
            $booking = FlightBooking::where('booking_reference', $bookingReference)->firstOrFail();

            if ($booking->status === 'confirmed') {
                return redirect()->route('flights.confirmation', ['bookingReference' => $bookingReference]);
            }

            $flight = FlightOfferMapper::mapDuffelOfferToArray($booking->flight_offer ?? []);
            $this->configurePesapal();

            $payment = Payment::create([
                'flight_booking_id' => $booking->id,
                'amount' => $booking->total_price,
                'status' => 'PENDING',
                'payment_method' => 'PESAPAL',
                'transactionid' => Pesapal::random_reference(),
                'user_id' => $booking->user_id,
            ]);

            $booking->update(['payment_id' => $payment->id]);

            $userCurrency = userCurrency();
            $amountUsd = (float) $booking->total_price;
            if ($userCurrency !== 'USD' && $booking->currency === 'USD') {
                $amountToSend = round(CurrencyConverter::convertFromBase($amountUsd, $userCurrency), 2);
                $currencyToSend = $userCurrency;
            } else {
                $amountToSend = $amountUsd;
                $currencyToSend = $booking->currency;
            }

            $name = $booking->passengers()->first();
            $details = [
                'amount' => $amountToSend,
                'description' => 'Flight booking ' . $booking->origin_code . ' to ' . $booking->destination_code . ' — ' . $booking->airline_name,
                'type' => 'MERCHANT',
                'first_name' => $name?->first_name ?? 'Guest',
                'last_name' => $name?->last_name ?? 'Traveler',
                'email' => $booking->contact_email,
                'phonenumber' => $booking->contact_phone,
                'reference' => $payment->transactionid,
                'currency' => $currencyToSend,
                'callback_url' => url('/payment/success'),
                'notification_url' => url('/payment/confirmation'),
            ];

            $iframe = Pesapal::makePayment($details);

            if (empty($iframe)) {
                throw new \RuntimeException('Unable to load the payment form. Please try again.');
            }

            return view('website.pages.flight-payment', compact('iframe', 'booking', 'payment', 'flight'));
        } catch (\Throwable $e) {
            Log::error('Flight payment init failed', ['reference' => $bookingReference, 'error' => $e->getMessage()]);

            return redirect()
                ->route('flights.index')
                ->with('error', 'Payment could not be started: ' . $e->getMessage());
        }
    }

    public function initializePayment($bookingReference)
    {
        return redirect()->route('flights.payment', ['bookingReference' => $bookingReference]);
    }

    public function paymentCallback(Request $request)
    {
        return redirect()->route('payment.success', $request->query());
    }

    public function confirmation($bookingReference)
    {
        $booking = FlightBooking::with('passengers')->where('booking_reference', $bookingReference)->first();

        if (! $booking) {
            return redirect()->route('flights.index')->with('error', 'Booking not found.');
        }

        $flight = FlightOfferMapper::mapDuffelOfferToArray($booking->flight_offer ?? []);

        return view('website.pages.flight-booking', [
            'flight' => $flight,
            'offerId' => $booking->flight_offer['id'] ?? null,
            'booking' => $booking,
            'confirmed' => true,
            'passengerCounts' => [
                'adults' => $booking->adults,
                'children' => $booking->children,
                'infants' => $booking->infants,
            ],
        ]);
    }

    public function myBookings()
    {
        return redirect()->route('flights.index')->with('info', 'View your saved flight requests by reference from our team.');
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
