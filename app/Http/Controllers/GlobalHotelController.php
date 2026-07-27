<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SupplierHotelBooking;
use App\Services\CurrencyConverter;
use App\Services\Hotels\HotelBookingService;
use App\Services\Hotels\HotelbedsContentService;
use App\Services\Hotels\HotelSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Knox\Pesapal\Facades\Pesapal;

class GlobalHotelController extends Controller
{
    public function __construct(
        protected HotelSearchService $searchService,
        protected HotelBookingService $bookingService,
    ) {}

    public function index()
    {
        if (! config('hotels.enabled', true)) {
            return redirect()->route('hotels')->with('error', 'Partner hotel search is temporarily unavailable.');
        }

        return view('website.pages.global-hotels');
    }

    public function show(string $hotelCode)
    {
        $rates = collect($this->searchService->ratesForHotel($hotelCode))
            ->unique('rate_key')
            ->sortBy('price')
            ->values()
            ->all();

        $criteria = session('hotel_search_criteria', []);

        if ($rates === []) {
            return redirect()
                ->route('hotels.global.index')
                ->with('error', 'Hotel rates expired. Please search again.');
        }

        $hotel = $rates[0];
        $profile = app(HotelbedsContentService::class)->profileForHotel($hotelCode);

        if (empty($profile['latitude']) && ! empty($hotel['latitude'])) {
            $profile['latitude'] = (string) $hotel['latitude'];
        }

        if (empty($profile['longitude']) && ! empty($hotel['longitude'])) {
            $profile['longitude'] = (string) $hotel['longitude'];
        }

        if ($profile['name'] === 'Hotel' && ! empty($hotel['hotel_name'])) {
            $profile['name'] = (string) $hotel['hotel_name'];
        }

        $minPrice = (float) collect($rates)->min('price');
        $currency = strtoupper((string) ($hotel['currency'] ?? 'USD'));
        $starRating = \App\Support\HotelOfferMapper::categoryStars(
            $profile['category_code'] ?? $hotel['category_code'] ?? null
        ) ?? 4;

        return view('website.pages.global-hotel-detail', compact(
            'hotel',
            'rates',
            'criteria',
            'hotelCode',
            'profile',
            'minPrice',
            'currency',
            'starRating',
        ));
    }

    public function selectRate(Request $request)
    {
        if (! $request->user()) {
            return back()->with('error', 'Please sign in to continue booking.');
        }

        $validated = $request->validate([
            'rate_key' => ['required', 'string', 'max:2000'],
        ]);

        $results = session('hotel_search_results', []);
        $offer = collect($results)->first(
            fn (array $row) => ($row['rate_key'] ?? '') === $validated['rate_key']
        );

        if (! $offer) {
            return back()->with('error', 'Selected rate is no longer available. Please search again.');
        }

        try {
            $offer = $this->bookingService->refreshOfferRate($offer);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        $token = $this->bookingService->storeCheckoutOffer($offer);

        return redirect()->route('hotels.global.checkout', ['token' => $token]);
    }

    public function checkout(string $token)
    {
        $offer = $this->bookingService->getCheckoutOffer($token);

        if (! $offer) {
            return redirect()
                ->route('hotels.global.index')
                ->with('error', 'Checkout session expired. Please search again.');
        }

        try {
            $offer = $this->bookingService->refreshOfferRate($offer);
            $this->bookingService->updateCheckoutOffer($token, $offer);
        } catch (\Throwable $e) {
            return redirect()
                ->route('hotels.global.index')
                ->with('error', $e->getMessage());
        }

        $criteria = session('hotel_search_criteria', []);
        $childAges = $criteria['childAges'] ?? [];

        return view('website.pages.global-hotel-booking', compact('offer', 'token', 'criteria', 'childAges'));
    }

    public function processBooking(Request $request, string $token)
    {
        $offer = $this->bookingService->getCheckoutOffer($token);

        if (! $offer) {
            return redirect()
                ->route('hotels.global.index')
                ->with('error', 'Checkout session expired. Please search again.');
        }

        $adults = max(1, (int) ($offer['adults'] ?? 2));
        $children = max(0, (int) ($offer['children'] ?? 0));

        $validated = $request->validate([
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'holder_name' => ['required', 'string', 'max:100'],
            'holder_surname' => ['required', 'string', 'max:100'],
            'guests' => ['nullable', 'array'],
            'guests.*.type' => ['required_with:guests', 'in:AD,CH,ad,child'],
            'guests.*.name' => ['required_with:guests', 'string', 'max:100'],
            'guests.*.surname' => ['required_with:guests', 'string', 'max:100'],
        ]);

        $criteria = session('hotel_search_criteria', []);
        $childAges = $criteria['childAges'] ?? array_fill(0, $children, 8);

        $validated['adults'] = $adults;
        $validated['children'] = $children;
        $validated['child_ages'] = $childAges;

        try {
            $offer = $this->bookingService->refreshOfferRate($offer);
            $booking = $this->bookingService->createPendingBooking($offer, $validated);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('hotels.global.payment', ['bookingReference' => $booking->booking_reference])
            ->with('success', 'Guest details saved. Complete payment to confirm your hotel.');
    }

    public function payment(string $bookingReference)
    {
        try {
            $booking = SupplierHotelBooking::where('booking_reference', $bookingReference)->firstOrFail();

            if ($booking->status === 'confirmed') {
                return redirect()->route('hotels.global.confirmation', ['bookingReference' => $bookingReference]);
            }

            $this->configurePesapal();

            $payment = Payment::create([
                'supplier_hotel_booking_id' => $booking->id,
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

            $details = [
                'amount' => $amountToSend,
                'description' => 'Hotel: ' . $booking->hotel_name . ' (' . $booking->check_in->format('M j') . ' – ' . $booking->check_out->format('M j') . ')',
                'type' => 'MERCHANT',
                'first_name' => explode(' ', $booking->supplier_payload['_checkout']['holder']['name'] ?? 'Guest')[0],
                'last_name' => $booking->supplier_payload['_checkout']['holder']['surname'] ?? 'Traveler',
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

            return view('website.pages.global-hotel-payment', compact('iframe', 'booking', 'payment'));
        } catch (\Throwable $e) {
            Log::error('Global hotel payment init failed', ['reference' => $bookingReference, 'error' => $e->getMessage()]);

            return redirect()
                ->route('hotels.global.index')
                ->with('error', 'Payment could not be started: ' . $e->getMessage());
        }
    }

    public function confirmation(string $bookingReference)
    {
        $booking = SupplierHotelBooking::where('booking_reference', $bookingReference)->first();

        if (! $booking) {
            return redirect()->route('hotels.global.index')->with('error', 'Booking not found.');
        }

        return view('website.pages.global-hotel-confirmation', compact('booking'));
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
