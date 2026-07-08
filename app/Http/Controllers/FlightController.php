<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightAffiliateClickRequest;
use App\Services\Flights\AffiliateTrackingService;
use App\Services\FlightService;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function __construct(
        protected FlightService $flightService,
        protected AffiliateTrackingService $tracking,
    ) {}

    /**
     * Display flights page with Livewire search.
     */
    public function index()
    {
        return view('website.pages.flights');
    }

    /**
     * Airport / city autocomplete for search fields.
     */
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

    /**
     * Track affiliate click and redirect (non-Livewire fallback).
     */
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

        return redirect()->away($data['affiliate_url']);
    }

    /**
     * Legacy routes now redirect to affiliate search flow.
     */
    public function show($flightId)
    {
        return redirect()->route('flights.index')->with('error', 'Please use Book Flight on the search results page.');
    }

    public function bookingForm($flightId)
    {
        return redirect()->route('flights.index')->with('error', 'Bookings are completed on our partner website.');
    }

    public function processBooking(Request $request)
    {
        return redirect()->route('flights.index')->with('error', 'Direct bookings are no longer available. Use Book Flight to continue on our partner site.');
    }

    public function payment($bookingReference)
    {
        return redirect()->route('flights.index')->with('error', 'Flight payments are handled by our affiliate partners.');
    }

    public function initializePayment($bookingReference)
    {
        return redirect()->route('flights.index');
    }

    public function paymentCallback(Request $request)
    {
        return redirect()->route('flights.index');
    }

    public function confirmation($bookingReference)
    {
        return redirect()->route('flights.index');
    }

    public function myBookings()
    {
        return redirect()->route('flights.index')->with('info', 'Flight bookings are completed on our partner website.');
    }
}
