<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FlightService;
use App\Models\FlightBooking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Knox\Pesapal\Facades\Pesapal;

class FlightController extends Controller
{
    protected $flightService;

    public function __construct(FlightService $flightService)
    {
        $this->flightService = $flightService;
    }

    /**
     * Display flights page with search
     */
    public function index(Request $request)
    {
        $flights = [];
        $destinations = $this->getPopularDestinations();
        $airlines = $this->getPopularAirlines();
        $error = null;

        // If search parameters are provided
        if ($request->has('origin') && $request->has('destination') && $request->has('departureDate')) {
            try {
                $flights = $this->flightService->searchFlights([
                    'origin' => $request->input('origin', 'ZNZ'),
                    'destination' => $request->input('destination'),
                    'departureDate' => $request->input('departureDate'),
                    'returnDate' => $request->input('returnDate'),
                    'adults' => $request->input('adults', 1),
                    'children' => $request->input('children', 0),
                    'infants' => $request->input('infants', 0),
                    'travelClass' => $request->input('travelClass', 'ECONOMY'),
                    'nonStop' => $request->input('nonStop', false),
                    'currency' => 'USD',
                    'max' => 50
                ]);

                // Store search results in session for booking
                session(['flight_search_results' => $flights]);
                
            } catch (\Exception $e) {
                $error = $e->getMessage();
                Log::error('Flight search error: ' . $e->getMessage());
            }
        }

        return view('website.pages.flights', compact('flights', 'destinations', 'airlines', 'error'));
    }

    /**
     * Show flight details
     */
    public function show($flightId, Request $request)
    {
        try {
            // Get flight offer from session (stored during search)
            $flights = session('flight_search_results', []);
            
            $flight = collect($flights)->firstWhere('id', $flightId);
            
            if (!$flight) {
                return redirect()->route('flights.index')->with('error', 'Flight not found. Please search again.');
            }

            return view('website.pages.flight-details', compact('flight'));

        } catch (\Exception $e) {
            return redirect()->route('flights.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Show booking form
     */
    public function bookingForm($flightId)
    {
        try {
            // Get flight offer from session
            $flights = session('flight_search_results', []);
            $flight = collect($flights)->firstWhere('id', $flightId);
            
            if (!$flight) {
                return redirect()->route('flights.index')->with('error', 'Flight not found. Please search again.');
            }

            return view('website.pages.flight-booking', compact('flight'));

        } catch (\Exception $e) {
            return redirect()->route('flights.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Process flight booking
     */
    public function processBooking(Request $request)
    {
        $validated = $request->validate([
            'flight_id' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'adults' => 'required|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:9',
            'infants' => 'nullable|integer|min:0|max:9',
            'passengers' => 'required|array|min:1',
            'passengers.*.first_name' => 'required|string|max:100',
            'passengers.*.last_name' => 'required|string|max:100',
            'passengers.*.date_of_birth' => 'required|date|before:today',
            'passengers.*.type' => 'required|in:adult,child,infant',
            'passengers.*.gender' => 'nullable|in:M,F',
            'passengers.*.passport_number' => 'nullable|string|max:50',
            'passengers.*.nationality' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Get flight offer from session
            $flights = session('flight_search_results', []);
            $flight = collect($flights)->firstWhere('id', $request->flight_id);
            
            if (!$flight) {
                throw new \Exception('Flight not found. Please search again.');
            }

            // Create booking
            $booking = $this->flightService->createBooking([
                'user_id' => auth()->id(),
                'flight_offer' => $flight['offer_data'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'adults' => $validated['adults'],
                'children' => $validated['children'] ?? 0,
                'infants' => $validated['infants'] ?? 0,
                'passengers' => $validated['passengers'],
                'travel_class' => $flight['cabin_class'],
            ]);

            // Create payment record
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'amount' => $booking->total_price,
                'currency' => $booking->currency,
                'status' => 'pending',
                'payment_method' => 'pesapal',
            ]);

            // Link payment to booking
            $booking->update(['payment_id' => $payment->id]);

            DB::commit();

            // Redirect to payment
            return redirect()->route('flights.payment', $booking->booking_reference);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Flight booking error: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show payment page
     */
    public function payment($bookingReference)
    {
        $booking = FlightBooking::where('booking_reference', $bookingReference)->firstOrFail();

        // Check if already paid
        if ($booking->status === 'confirmed') {
            return redirect()->route('flights.confirmation', $bookingReference);
        }

        return view('website.pages.flight-payment', compact('booking'));
    }

    /**
     * Initialize Pesapal payment
     */
    public function initializePayment($bookingReference)
    {
        try {
            $booking = FlightBooking::where('booking_reference', $bookingReference)->firstOrFail();

            // Prepare Pesapal order
            $order = [
                'id' => $booking->booking_reference,
                'currency' => $booking->currency,
                'amount' => $booking->total_price,
                'description' => "Flight Booking - {$booking->flight_number} ({$booking->origin_code} to {$booking->destination_code})",
                'callback_url' => route('flights.payment.callback'),
                'notification_id' => config('pesapal.ipn_id'),
                'billing_address' => [
                    'email_address' => $booking->contact_email,
                    'phone_number' => $booking->contact_phone,
                    'country_code' => 'TZ',
                    'first_name' => $booking->passengers->first()->first_name ?? 'Customer',
                    'last_name' => $booking->passengers->first()->last_name ?? '',
                ]
            ];

            // Submit order to Pesapal
            $response = Pesapal::submitOrder($order);

            if ($response && isset($response['redirect_url'])) {
                // Store order tracking ID
                $booking->payment->update([
                    'transaction_id' => $response['order_tracking_id']
                ]);

                // Redirect to Pesapal
                return redirect($response['redirect_url']);
            }

            throw new \Exception('Failed to initialize payment');

        } catch (\Exception $e) {
            Log::error('Payment initialization error: ' . $e->getMessage());
            return back()->with('error', 'Payment initialization failed. Please try again.');
        }
    }

    /**
     * Handle Pesapal payment callback
     */
    public function paymentCallback(Request $request)
    {
        try {
            $orderTrackingId = $request->input('OrderTrackingId');
            
            if (!$orderTrackingId) {
                throw new \Exception('Invalid payment callback');
            }

            // Get transaction status from Pesapal
            $transactionStatus = Pesapal::getTransactionStatus($orderTrackingId);

            // Find payment by tracking ID
            $payment = Payment::where('transaction_id', $orderTrackingId)->firstOrFail();
            $booking = FlightBooking::where('payment_id', $payment->id)->firstOrFail();

            if ($transactionStatus['payment_status_description'] === 'Completed') {
                // Update payment status
                $payment->update([
                    'status' => 'completed',
                    'payment_date' => now(),
                ]);

                // Confirm booking with Amadeus
                $confirmed = $this->flightService->confirmBookingWithAmadeus($booking);

                if ($confirmed) {
                    // Send confirmation email
                    // Mail::to($booking->contact_email)->send(new FlightBookingConfirmation($booking));
                    
                    return redirect()->route('flights.confirmation', $booking->booking_reference);
                } else {
                    // Payment received but booking failed - needs manual processing
                    return redirect()->route('flights.confirmation', $booking->booking_reference)
                        ->with('warning', 'Payment received. Your booking is being processed and you will receive confirmation shortly.');
                }
            } else {
                // Payment failed
                $payment->update(['status' => 'failed']);
                
                return redirect()->route('flights.payment', $booking->booking_reference)
                    ->with('error', 'Payment failed. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('flights.index')->with('error', 'Payment processing error. Please contact support.');
        }
    }

    /**
     * Show booking confirmation
     */
    public function confirmation($bookingReference)
    {
        $booking = FlightBooking::with('passengers', 'payment')
            ->where('booking_reference', $bookingReference)
            ->firstOrFail();

        return view('website.pages.flight-confirmation', compact('booking'));
    }

    /**
     * Get user's flight bookings
     */
    public function myBookings()
    {
        $bookings = FlightBooking::with('passengers', 'payment')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('website.pages.my-flight-bookings', compact('bookings'));
    }

    /**
     * Get popular destinations
     */
    protected function getPopularDestinations(): array
    {
        return [
            'DAR' => 'Dar es Salaam',
            'JRO' => 'Kilimanjaro',
            'NBO' => 'Nairobi',
            'MBA' => 'Mombasa',
            'DXB' => 'Dubai',
            'DOH' => 'Doha',
            'ADD' => 'Addis Ababa',
            'IST' => 'Istanbul',
            'AMS' => 'Amsterdam',
            'LHR' => 'London',
        ];
    }

    /**
     * Get popular airlines
     */
    protected function getPopularAirlines(): array
    {
        return config('amadeus.tanzania_airlines', [
            'TC' => 'Air Tanzania',
            'PW' => 'Precision Air',
            'ET' => 'Ethiopian Airlines',
            'KQ' => 'Kenya Airways',
            'EK' => 'Emirates',
            'QR' => 'Qatar Airways',
            'TK' => 'Turkish Airlines',
        ]);
    }
}
