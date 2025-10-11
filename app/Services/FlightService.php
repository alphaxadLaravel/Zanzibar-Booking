<?php

namespace App\Services;

use Amadeus\Amadeus;
use Amadeus\Exceptions\ResponseException;
use App\Models\FlightSearch;
use App\Models\FlightBooking;
use App\Models\FlightPassenger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FlightService
{
    protected $amadeus;

    public function __construct()
    {
        $builder = Amadeus::builder(
            config('amadeus.api_key'),
            config('amadeus.api_secret')
        );

        // Set production environment if configured
        if (config('amadeus.environment') === 'production') {
            $builder->setProductionEnvironment();
        }
        // Otherwise defaults to test environment

        $this->amadeus = $builder->build();
    }

    /**
     * Search for flights
     *
     * @param array $params
     * @return array
     */
    public function searchFlights(array $params): array
    {
        try {
            // Log search for analytics
            $this->logSearch($params);

            // Build search parameters
            $searchParams = [
                'originLocationCode' => $params['origin'],
                'destinationLocationCode' => $params['destination'],
                'departureDate' => $params['departureDate'],
                'adults' => $params['adults'] ?? 1,
                'max' => $params['max'] ?? config('amadeus.defaults.max_results', 50),
            ];

            // Add optional parameters
            if (isset($params['returnDate'])) {
                $searchParams['returnDate'] = $params['returnDate'];
            }
            if (isset($params['children'])) {
                $searchParams['children'] = $params['children'];
            }
            if (isset($params['infants'])) {
                $searchParams['infants'] = $params['infants'];
            }
            if (isset($params['travelClass'])) {
                $searchParams['travelClass'] = $params['travelClass'];
            }
            if (isset($params['nonStop'])) {
                $searchParams['nonStop'] = $params['nonStop'];
            }
            if (isset($params['currency'])) {
                $searchParams['currencyCode'] = $params['currency'];
            }

            // Call Amadeus API
            $response = $this->amadeus->getShopping()->getFlightOffers()->get($searchParams);

            // Parse and return results
            return $this->parseFlightOffers($response[0]);

        } catch (ResponseException $e) {
            Log::error('Amadeus API Error: ' . $e->getMessage(), [
                'params' => $params,
                'response' => $e->getResponse()
            ]);
            
            throw new \Exception('Unable to search flights. Please try again later.');
        }
    }

    /**
     * Get flight price for specific offer
     *
     * @param array $flightOffer
     * @return array
     */
    public function getPriceForOffer(array $flightOffer): array
    {
        try {
            $response = $this->amadeus->getShopping()->getFlightOffers()->getPricing()->post(
                json_encode([
                    'data' => [
                        'type' => 'flight-offers-pricing',
                        'flightOffers' => [$flightOffer]
                    ]
                ])
            );

            return $response[0];

        } catch (ResponseException $e) {
            Log::error('Amadeus Pricing API Error: ' . $e->getMessage());
            throw new \Exception('Unable to get flight price. Please try again.');
        }
    }

    /**
     * Create flight booking
     *
     * @param array $data
     * @return FlightBooking
     */
    public function createBooking(array $data): FlightBooking
    {
        try {
            // Generate booking reference
            $bookingReference = FlightBooking::generateBookingReference();

            // Extract flight details from offer
            $flightOffer = $data['flight_offer'];
            $segment = $flightOffer['itineraries'][0]['segments'][0];
            
            // Create booking
            $booking = FlightBooking::create([
                'booking_reference' => $bookingReference,
                'user_id' => $data['user_id'] ?? null,
                'flight_number' => $segment['carrierCode'] . $segment['number'],
                'airline_code' => $segment['carrierCode'],
                'airline_name' => $this->getAirlineName($segment['carrierCode']),
                'origin_code' => $segment['departure']['iataCode'],
                'origin_name' => $segment['departure']['iataCode'],
                'destination_code' => $segment['arrival']['iataCode'],
                'destination_name' => $segment['arrival']['iataCode'],
                'departure_datetime' => $segment['departure']['at'],
                'arrival_datetime' => $segment['arrival']['at'],
                'duration' => $flightOffer['itineraries'][0]['duration'],
                'aircraft' => $segment['aircraft']['code'] ?? null,
                'stops' => count($flightOffer['itineraries'][0]['segments']) - 1,
                'status' => 'pending',
                'travel_class' => $data['travel_class'] ?? 'ECONOMY',
                'adults' => $data['adults'] ?? 1,
                'children' => $data['children'] ?? 0,
                'infants' => $data['infants'] ?? 0,
                'base_price' => $flightOffer['price']['base'],
                'taxes' => $flightOffer['price']['total'] - $flightOffer['price']['base'],
                'total_price' => $flightOffer['price']['total'],
                'currency' => $flightOffer['price']['currency'],
                'flight_offer' => $flightOffer,
                'contact_email' => $data['contact_email'],
                'contact_phone' => $data['contact_phone'],
            ]);

            // Add passengers
            if (isset($data['passengers'])) {
                foreach ($data['passengers'] as $passengerData) {
                    FlightPassenger::create([
                        'flight_booking_id' => $booking->id,
                        'type' => $passengerData['type'] ?? 'adult',
                        'title' => $passengerData['title'] ?? null,
                        'first_name' => $passengerData['first_name'],
                        'last_name' => $passengerData['last_name'],
                        'date_of_birth' => $passengerData['date_of_birth'],
                        'gender' => $passengerData['gender'] ?? null,
                        'nationality' => $passengerData['nationality'] ?? null,
                        'passport_number' => $passengerData['passport_number'] ?? null,
                        'passport_country' => $passengerData['passport_country'] ?? null,
                        'passport_expiry' => $passengerData['passport_expiry'] ?? null,
                        'email' => $passengerData['email'] ?? $data['contact_email'],
                        'phone' => $passengerData['phone'] ?? $data['contact_phone'],
                    ]);
                }
            }

            return $booking;

        } catch (\Exception $e) {
            Log::error('Booking Creation Error: ' . $e->getMessage());
            throw new \Exception('Unable to create booking. Please try again.');
        }
    }

    /**
     * Confirm booking with Amadeus after payment
     *
     * @param FlightBooking $booking
     * @return bool
     */
    public function confirmBookingWithAmadeus(FlightBooking $booking): bool
    {
        try {
            // Prepare travelers data
            $travelers = [];
            foreach ($booking->passengers as $index => $passenger) {
                $travelers[] = [
                    'id' => (string)($index + 1),
                    'dateOfBirth' => $passenger->date_of_birth->format('Y-m-d'),
                    'name' => [
                        'firstName' => $passenger->first_name,
                        'lastName' => $passenger->last_name
                    ],
                    'gender' => $passenger->gender ?? 'MALE',
                    'contact' => [
                        'emailAddress' => $passenger->email,
                        'phones' => [
                            [
                                'deviceType' => 'MOBILE',
                                'countryCallingCode' => '255',
                                'number' => $passenger->phone
                            ]
                        ]
                    ],
                    'documents' => $passenger->passport_number ? [
                        [
                            'documentType' => 'PASSPORT',
                            'number' => $passenger->passport_number,
                            'expiryDate' => $passenger->passport_expiry?->format('Y-m-d'),
                            'issuanceCountry' => $passenger->passport_country,
                            'nationality' => $passenger->nationality,
                            'holder' => true
                        ]
                    ] : []
                ];
            }

            // Create order with Amadeus
            $response = $this->amadeus->getBooking()->getFlightOrders()->post(
                json_encode([
                    'data' => [
                        'type' => 'flight-order',
                        'flightOffers' => [$booking->flight_offer],
                        'travelers' => $travelers
                    ]
                ])
            );

            // Update booking with Amadeus response
            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'amadeus_order_id' => $response[0]['id'],
                'amadeus_response' => $response[0]
            ]);

            return true;

        } catch (ResponseException $e) {
            Log::error('Amadeus Booking Confirmation Error: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'response' => $e->getResponse()
            ]);
            return false;
        }
    }

    /**
     * Parse flight offers from Amadeus response
     *
     * @param array $data
     * @return array
     */
    protected function parseFlightOffers(array $data): array
    {
        $flights = [];

        if (!isset($data['data']) || empty($data['data'])) {
            return $flights;
        }

        foreach ($data['data'] as $offer) {
            $segment = $offer['itineraries'][0]['segments'][0];
            $lastSegment = end($offer['itineraries'][0]['segments']);

            $flights[] = [
                'id' => $offer['id'],
                'flight_number' => $segment['carrierCode'] . $segment['number'],
                'airline' => $this->getAirlineName($segment['carrierCode']),
                'airline_code' => $segment['carrierCode'],
                'departure' => [
                    'airport' => $segment['departure']['iataCode'],
                    'city' => $this->getAirportCity($segment['departure']['iataCode']),
                    'time' => date('H:i', strtotime($segment['departure']['at'])),
                    'date' => date('Y-m-d', strtotime($segment['departure']['at'])),
                    'datetime' => $segment['departure']['at']
                ],
                'arrival' => [
                    'airport' => $lastSegment['arrival']['iataCode'],
                    'city' => $this->getAirportCity($lastSegment['arrival']['iataCode']),
                    'time' => date('H:i', strtotime($lastSegment['arrival']['at'])),
                    'date' => date('Y-m-d', strtotime($lastSegment['arrival']['at'])),
                    'datetime' => $lastSegment['arrival']['at']
                ],
                'duration' => $this->formatDuration($offer['itineraries'][0]['duration']),
                'stops' => count($offer['itineraries'][0]['segments']) - 1,
                'aircraft' => $segment['aircraft']['code'] ?? 'N/A',
                'price' => (float)$offer['price']['total'],
                'currency' => $offer['price']['currency'],
                'status' => 'On Time',
                'cabin_class' => $offer['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin'] ?? 'ECONOMY',
                'available_seats' => $offer['numberOfBookableSeats'] ?? null,
                'offer_data' => $offer // Store full offer for booking
            ];
        }

        return $flights;
    }

    /**
     * Log flight search for analytics
     *
     * @param array $params
     * @return void
     */
    protected function logSearch(array $params): void
    {
        try {
            FlightSearch::create([
                'user_id' => auth()->id(),
                'origin_code' => $params['origin'],
                'origin_name' => $this->getAirportCity($params['origin']),
                'destination_code' => $params['destination'],
                'destination_name' => $this->getAirportCity($params['destination']),
                'departure_date' => $params['departureDate'],
                'return_date' => $params['returnDate'] ?? null,
                'adults' => $params['adults'] ?? 1,
                'children' => $params['children'] ?? 0,
                'infants' => $params['infants'] ?? 0,
                'travel_class' => $params['travelClass'] ?? 'ECONOMY',
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log flight search: ' . $e->getMessage());
        }
    }

    /**
     * Get airline name from code
     *
     * @param string $code
     * @return string
     */
    protected function getAirlineName(string $code): string
    {
        $airlines = config('amadeus.tanzania_airlines', []);
        return $airlines[$code] ?? $code;
    }

    /**
     * Get airport city name
     *
     * @param string $code
     * @return string
     */
    protected function getAirportCity(string $code): string
    {
        $airports = config('amadeus.tanzania_airports', []);
        
        // Check if it's a known Tanzania airport
        if (isset($airports[$code])) {
            return explode(' - ', $airports[$code])[0];
        }

        // Cache other airport lookups
        return Cache::remember("airport_city_{$code}", 86400, function () use ($code) {
            // You can integrate with airport lookup API here
            return $code;
        });
    }

    /**
     * Format ISO 8601 duration to human readable
     *
     * @param string $duration
     * @return string
     */
    protected function formatDuration(string $duration): string
    {
        preg_match('/PT(\d+H)?(\d+M)?/', $duration, $matches);
        
        $hours = isset($matches[1]) ? rtrim($matches[1], 'H') : 0;
        $minutes = isset($matches[2]) ? rtrim($matches[2], 'M') : 0;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }
}

