<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Room;
use App\Models\Tours;
use App\Models\Car;
use App\Services\RoomPriceService;
use App\Models\Blog;
use App\Models\Near;
use App\Models\DealReviews;
use App\Models\Pages;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Hashids\Hashids;

class WebsiteController extends Controller
{
    /**
     * Create Hashids instance
     */
    private function getHashids()
    {
        return new Hashids('MchungajiZanzibarBookings', 10);
    }

    //index
    public function index()
    {
        // Fetch categories for property types (hotels, apartments, packages, activities, resorts)
        $propertyCategories = Category::whereIn('type', ['hotel', 'apartment', 'package', 'activity', 'resort'])
            ->limit(6)
            ->get();

        // Fetch all deals (hotels/apartments)
        $featuredDeals = Deal::whereIn('type', ['hotel', 'apartment'])
            ->where('status', 1)
            ->with(['category', 'photos'])
            ->limit(6)
            ->get();

        // Fetch package and activity categories
        $tourCategories = Category::where('type', 'tour')
            ->limit(6)
            ->get();

        // Fetch all packages and activities
        $featuredTours = Deal::whereIn('type', ['package', 'activity'])
            ->where('status', 1)
            ->with(['category', 'photos', 'tours'])
            ->limit(6)
            ->get();

        // Fetch car categories
        $carCategories = Category::where('type', 'car')
            ->limit(6)
            ->get();

        // Fetch all cars
        $featuredCars = Deal::where('type', 'car')
            ->where('status', 1)
            ->with(['category', 'photos', 'car'])
            ->limit(6)
            ->get();

        $hashids = $this->getHashids();

        return view('website.pages.index', compact(
            'propertyCategories',
            'featuredDeals',
            'tourCategories',
            'featuredTours',
            'carCategories',
            'featuredCars',
            'hashids'
        ));
    }

    // contactUs
    public function contactUs()
    {
        return view('website.pages.contact_us');
    }

    // submitContactForm
    public function submitContactForm(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        try {
            ContactMessage::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'subject' => $request->subject,
                'content' => $request->content,
                'status' => 'new',
            ]);

            return redirect()->back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
        } catch (\Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send message. Please try again.');
        }
    }

    // Convert slug to page name
    private function slugToPageName($slug)
    {
        $slugMap = [
            'about-us' => 'About Us',
            'become-a-partner' => 'Become a Partner',
            'our-commitment' => 'Our Commitment',
            'terms-conditions' => 'Terms & Conditions',
            'privacy-policy' => 'Privacy Policy',
        ];

        return $slugMap[$slug] ?? null;
    }

    // Dynamic page display
    public function showPage($slug)
    {
        $pageName = $this->slugToPageName($slug);
        
        if (!$pageName) {
            abort(404, 'Page not found');
        }

        $page = Pages::where('page', $pageName)->first();
        
        if (!$page) {
            abort(404, 'Page content not found');
        }

        return view('website.pages.page', compact('page', 'slug'));
    }

    // blog
    public function blog()
    {
        // Fetch published blog posts with pagination
        $blogs = Blog::where('status', 1)
            ->with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        // Fetch blog categories for sidebar
        $categories = Category::where('type', 'blog')
            ->get();

        return view('website.pages.blog', compact('blogs', 'categories'));
    }

    // viewBlog
    public function viewBlog($id)
    {
        // Get blog hashid from route parameter and decode
        $hashids = $this->getHashids();
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            abort(404, 'Blog post not found');
        }
        $blogId = $decodedIds[0];
        
        // Fetch specific blog post
        $blog = Blog::where('status', 1)
            ->with(['user', 'category'])
            ->findOrFail($blogId);

        // Fetch recent blog posts for sidebar
        $recentBlogs = Blog::where('status', 1)
            ->where('id', '!=', $blogId)
            ->with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Fetch blog categories for sidebar
        $categories = Category::where('type', 'blog')
            ->get();

        return view('website.pages.view_post', compact('blog', 'recentBlogs', 'categories'));
    }

    // hotels
    public function hotels()
    {
        return view('website.pages.hotels');
    }

    // apartments
    public function apartments()
    {
        return view('website.pages.apartments');
    }

    // tours
    public function tours()
    {
        return view('website.pages.tours');
    }

    public function activities()
    {
        return view('website.pages.activities');
    }

    public function packages()
    {
        return view('website.pages.packages');
    }

    

    // viewHotel
    public function viewHotel($id)
    {
        $hashids = $this->getHashids();
        
        // Decode the hashed ID
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            abort(404, 'Hotel not found');
        }
        $hotelId = $decodedIds[0];
        
        // Fetch specific hotel with all relationships
        $hotel = Deal::whereIn('type', ['hotel', 'apartment'])
            ->with(['category', 'photos', 'rooms.photos', 'features', 'nearbyLocations'])
            ->findOrFail($hotelId);
            
        // Paginate reviews separately
        $paginatedReviews = $hotel->approvedReviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Ensure we have rooms data - if no rooms exist, we'll show default room in view
        $rooms = $hotel->rooms()->active()->available()->with('priceIntervals')->get();

        // Fetch nearby deals from the nears table
        $nearbyDeals = Near::where('deal_id', $hotelId)
            ->with(['nearDeal.category', 'nearDeal.photos', 'nearDeal.tours'])
            ->get();

        // Separate nearby deals by type
        $nearbyHotels = collect();
        $nearbyTours = collect();

        foreach ($nearbyDeals as $near) {
            if ($near->type === 'hotel' || $near->type === 'apartment') {
                $nearbyHotels->push($near->nearDeal);
            } elseif ($near->type === 'tour') {
                $nearbyTours->push($near->nearDeal);
            }
        }

        // Limit to reasonable numbers
        $nearbyHotels = $nearbyHotels->take(3);
        $nearbyTours = $nearbyTours->take(6);

        return view('website.pages.view_hotel', compact('hotel', 'rooms', 'nearbyHotels', 'nearbyTours', 'paginatedReviews', 'hashids'));
    }

    /**
     * Get calculated room price for given dates and guests (JSON API)
     */
    public function getRoomPrice(Request $request, $roomId)
    {
        $room = Room::with('priceIntervals')->find($roomId);
        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        $checkIn = $request->query('check_in');
        $checkOut = $request->query('check_out');
        $numberRooms = (int) $request->query('number_rooms', 1);
        $adults = (int) $request->query('adults', 1);
        $children = (int) $request->query('children', 0);

        if (!$checkIn || !$checkOut) {
            return response()->json(['error' => 'check_in and check_out are required'], 422);
        }

        $service = new RoomPriceService();
        $totalPrice = $service->calculateTotalPrice($room, $checkIn, $checkOut, $numberRooms, $adults, $children);

        return response()->json([
            'total_price' => $totalPrice,
            'room_id' => $room->id,
        ]);
    }

    /**
     * Get room prices for a month (for calendar display)
     */
    public function getRoomPricesCalendar(Request $request, $roomId)
    {
        $room = Room::with('priceIntervals')->find($roomId);
        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        $year = (int) $request->query('year', date('Y'));
        $month = (int) $request->query('month', date('n'));
        $adults = (int) $request->query('adults', 1);
        $children = (int) $request->query('children', 0);

        $service = new RoomPriceService();
        $prices = $service->getPricesForMonth($room, $year, $month, $adults, $children);

        return response()->json([
            'year' => $year,
            'month' => $month,
            'prices' => $prices,
        ]);
    }

    // viewApartment
    public function viewApartment($id)
    {
        $hashids = $this->getHashids();
        
        // Decode the hashed ID
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            abort(404, 'Apartment not found');
        }
        $apartmentId = $decodedIds[0];
        
        // Fetch specific apartment with all relationships
        $apartment = Deal::where('type', 'apartment')
            ->with(['category', 'photos', 'features'])
            ->findOrFail($apartmentId);

        // Paginate reviews separately
        $paginatedReviews = $apartment->approvedReviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Fetch nearby deals from the nears table
        $nearbyDeals = Near::where('deal_id', $apartmentId)
            ->with(['nearDeal.category', 'nearDeal.photos', 'nearDeal.tours'])
            ->get();

        // Separate nearby deals by type
        $nearbyHotels = collect();
        $nearbyTours = collect();

        foreach ($nearbyDeals as $near) {
            if ($near->type === 'hotel' || $near->type === 'apartment') {
                $nearbyHotels->push($near->nearDeal);
            } elseif ($near->type === 'package' || $near->type === 'activity') {
                $nearbyTours->push($near->nearDeal);
            }
        }

        // Limit to reasonable numbers
        $nearbyHotels = $nearbyHotels->take(3);
        $nearbyTours = $nearbyTours->take(6);

        return view('website.pages.view_apartment', compact('apartment', 'nearbyHotels', 'nearbyTours', 'paginatedReviews', 'hashids'));
    }

    // cars
    public function cars()
    {
        return view('website.pages.cars');
    }

    /**
     * Display flights page with real-time flight data from API
     * 
     * This method fetches ONLY real data from AviationStack API.
     * No hardcoded or sample data is used.
     * 
     * To enable real API data:
     * 1. Get a free API key from https://aviationstack.com/
     * 2. Add AVIATIONSTACK_API_KEY=your_api_key to your .env file
     * 3. The system will automatically fetch real flight data
     * 4. If API fails, empty data is returned (no fallback)
     */
    public function flights()
    {
        try {
            // Fetch flights from real API only
            $flights = $this->fetchFlightsFromAPI();
            
            // Get unique airlines for filter
            $airlines = $flights->pluck('airline')->unique()->sort()->values();

            // Get unique destinations for filter
            $destinations = $flights->pluck('arrival.city')->unique()->sort()->values();

            return view('website.pages.flights', compact('flights', 'airlines', 'destinations'));
            
        } catch (\Exception $e) {
            Log::error('Flights API Error: ' . $e->getMessage());
            
            // Return empty data on error - no fallback to hardcoded data
            $flights = collect([]);
            $airlines = collect([]);
            $destinations = collect([]);
            
            return view('website.pages.flights', compact('flights', 'airlines', 'destinations'));
        }
    }

    // Fetch flights from real API
    private function fetchFlightsFromAPI($origin = 'ZNZ', $destination = null, $date = null)
    {
        try {
            // Using AviationStack API for flight schedules
            // You can get a free API key from https://aviationstack.com/
            $apiKey = config('services.aviationstack.api_key', 'your_api_key_here');
            
            if ($apiKey === 'your_api_key_here') {
                // If no API key is configured, return empty collection
                Log::warning('AviationStack API key not configured');
                return collect([]);
            }
            
            $client = new \GuzzleHttp\Client();
            
            // Build query parameters
            $queryParams = [
                'access_key' => $apiKey,
                'dep_iata' => $origin,
                'limit' => 20
            ];
            
            if ($destination) {
                $queryParams['arr_iata'] = $destination;
            }
            
            if ($date) {
                $queryParams['dep_date'] = $date;
            } else {
                // Default to today's date
                $queryParams['dep_date'] = now()->format('Y-m-d');
            }
            
            $response = $client->get('http://api.aviationstack.com/v1/flights', [
                'query' => $queryParams,
                'timeout' => 10
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if (isset($data['data']) && is_array($data['data'])) {
                return $this->formatAviationStackData($data['data']);
            }
            
            return collect([]);
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Flight API Request Error: ' . $e->getMessage());
            return collect([]);
        } catch (\Exception $e) {
            Log::error('Flight API Error: ' . $e->getMessage());
            return collect([]);
        }
    }

    // Format AviationStack API response data
    private function formatAviationStackData($apiData)
    {
        $flights = collect();
        
        foreach ($apiData as $flight) {
            try {
                $departure = $flight['departure'] ?? [];
                $arrival = $flight['arrival'] ?? [];
                $airline = $flight['airline'] ?? [];
                $flightInfo = $flight['flight'] ?? [];
                
                $flights->push([
                    'id' => $flight['flight']['number'] ?? uniqid(),
                    'airline' => $airline['name'] ?? 'Unknown Airline',
                    'flight_number' => $flightInfo['iata'] ?? $flightInfo['number'] ?? 'N/A',
                    'departure' => [
                        'airport' => $departure['iata'] ?? 'N/A',
                        'city' => $departure['airport'] ?? 'Unknown',
                        'time' => isset($departure['scheduled']) ? date('H:i', strtotime($departure['scheduled'])) : 'N/A',
                        'date' => isset($departure['scheduled']) ? date('Y-m-d', strtotime($departure['scheduled'])) : 'N/A'
                    ],
                    'arrival' => [
                        'airport' => $arrival['iata'] ?? 'N/A',
                        'city' => $arrival['airport'] ?? 'Unknown',
                        'time' => isset($arrival['scheduled']) ? date('H:i', strtotime($arrival['scheduled'])) : 'N/A',
                        'date' => isset($arrival['scheduled']) ? date('Y-m-d', strtotime($arrival['scheduled'])) : 'N/A'
                    ],
                    'duration' => $this->calculateDuration($departure['scheduled'] ?? null, $arrival['scheduled'] ?? null),
                    'price' => null, // Price not available from AviationStack API
                    'currency' => null, // Currency not available from AviationStack API
                    'stops' => null, // Stop information not available from AviationStack API
                    'aircraft' => $flight['aircraft']['name'] ?? 'Unknown',
                    'status' => $this->getFlightStatus($departure['delay'] ?? null, $flight['flight_status'] ?? null)
                ]);
            } catch (\Exception $e) {
                Log::warning('Error formatting flight data: ' . $e->getMessage());
                continue;
            }
        }
        
        return $flights;
    }
    
    // Calculate flight duration
    private function calculateDuration($departureTime, $arrivalTime)
    {
        if (!$departureTime || !$arrivalTime) {
            return 'N/A';
        }
        
        try {
            $departure = new \DateTime($departureTime);
            $arrival = new \DateTime($arrivalTime);
            $diff = $departure->diff($arrival);
            
            $hours = $diff->h;
            $minutes = $diff->i;
            
            return $hours . 'h ' . $minutes . 'm';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
    
    
    // Get flight status
    private function getFlightStatus($delay, $flightStatus)
    {
        if ($delay && $delay > 0) {
            return 'Delayed';
        }
        
        switch ($flightStatus) {
            case 'active':
            case 'landed':
                return 'On Time';
            case 'cancelled':
                return 'Cancelled';
            case 'incident':
                return 'Delayed';
            default:
                return 'On Time';
        }
    }
    

    // viewCar
    public function viewCar($id)
    {
        $hashids = $this->getHashids();
        
        // Decode the hashed ID
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            abort(404, 'Car not found');
        }
        $carId = $decodedIds[0];

        $car = Deal::where('type', 'car')
            ->where('id', $carId)
            ->with(['category', 'photos', 'car', 'features'])
            ->firstOrFail();

        // Paginate reviews separately
        $paginatedReviews = $car->approvedReviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Fetch nearby deals from the nears table
        $nearbyDeals = Near::where('deal_id', $carId)
            ->with(['nearDeal.category', 'nearDeal.photos', 'nearDeal.car'])
            ->get();

        // Separate nearby deals by type
        $nearbyHotels = collect();
        $nearbyTours = collect();
        $nearbyCars = collect();

        foreach ($nearbyDeals as $near) {
            if ($near->type === 'hotel' || $near->type === 'apartment') {
                $nearbyHotels->push($near->nearDeal);
            } elseif ($near->type === 'package' || $near->type === 'activity') {
                $nearbyTours->push($near->nearDeal);
            } elseif ($near->type === 'car') {
                $nearbyCars->push($near->nearDeal);
            }
        }

        // If no nearby cars, get random cars
        if ($nearbyCars->isEmpty()) {
            $nearbyCars = Deal::where('type', 'car')
                ->where('id', '!=', $car->id)
                ->with(['category', 'photos'])
                ->limit(3)
                ->get();
        }

        // Limit to reasonable numbers
        $nearbyHotels = $nearbyHotels->take(3);
        $nearbyTours = $nearbyTours->take(3);
        $nearbyCars = $nearbyCars->take(3);

        return view('website.pages.view_car', compact('car', 'nearbyHotels', 'nearbyTours', 'nearbyCars', 'paginatedReviews', 'hashids'));
    }




    // confirmBooking
    public function confirmBooking(Request $request)
    {
        // Get deal ID from request or session
        $dealId = $request->get('deal_id') ?? session('booking_deal_id');
        
        if (!$dealId) {
            return redirect()->route('index')->with('error', 'No deal selected for booking');
        }
        
        $deal = Deal::with(['category', 'photos'])->find($dealId);
        
        if (!$deal) {
            return redirect()->route('index')->with('error', 'Deal not found');
        }
        
        return view('website.pages.confirm_booking', compact('deal'));
    }

    // processBooking
    public function processBooking(Request $request)
    {
        // Validate the request
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'deal_type' => 'required|string',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:pesapal,offline',
            'agree_terms' => 'required|accepted',
        ]);

        // Get deal details
        $deal = Deal::findOrFail($request->deal_id);
        
        // Generate booking reference
        $bookingRef = 'ZB' . strtoupper(substr(md5(time() . $request->email), 0, 8));
        
        // Prepare booking data
        $bookingData = [
            'deal_id' => $deal->id,
            'deal_type' => $deal->type,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'payment_method' => $request->payment_method,
            'special_requirements' => $request->special_requirements ?? '',
            'booking_reference' => $bookingRef,
            'total_amount' => $deal->base_price,
            'status' => $request->payment_method === 'offline' ? 'confirmed' : 'pending',
        ];

        // Add deal-specific data
        if ($deal->type === 'tour') {
            $bookingData['check_in'] = $request->check_in;
            $bookingData['guests'] = $request->guests;
        } elseif ($deal->type === 'car') {
            $bookingData['pickup_date'] = $request->pickup_date;
            $bookingData['return_date'] = $request->return_date;
        } elseif (in_array($deal->type, ['hotel', 'apartment'])) {
            $bookingData['check_in'] = $request->check_in;
            $bookingData['check_out'] = $request->check_out;
            $bookingData['guests'] = $request->guests;
            $bookingData['rooms'] = $request->rooms ?? 1;
        }

        // Process the booking based on payment method
        if ($request->payment_method === 'pesapal') {
            // Store booking data in session for payment processing
            session(['pending_booking' => $bookingData]);
            // Redirect to Pesapal payment gateway
            return redirect()->route('pesapal.payment')->with('booking_data', $bookingData);
        } else {
            // Process offline payment - save booking directly
            // Note: You'll need to create a Booking model and save the data
            session(['booking_confirmation' => $bookingData]);
            return redirect()->route('booking.success')->with('success', 'Your booking has been confirmed! You will pay on arrival.');
        }
    }

    //viewTour
    public function viewTour($id)
    {
        $hashids = $this->getHashids();
        
        // Decode the hashed ID
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            abort(404, 'Tour not found');
        }
        $tourId = $decodedIds[0];

        $tour = Deal::whereIn('type', ['package', 'activity'])
            ->where('id', $tourId)
            ->with(['category', 'photos', 'tours', 'features', 'tourIncludes.feature'])
            ->firstOrFail();

        // Paginate reviews separately
        $paginatedReviews = $tour->approvedReviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Fetch nearby deals from the nears table
        $nearbyDeals = Near::where('deal_id', $tourId)
            ->with(['nearDeal.category', 'nearDeal.photos', 'nearDeal.tours'])
            ->get();

        // Separate nearby deals by type
        $nearbyHotels = collect();
        $nearbyTours = collect();

        foreach ($nearbyDeals as $near) {
            if ($near->type === 'hotel' || $near->type === 'apartment') {
                $nearbyHotels->push($near->nearDeal);
            } elseif ($near->type === 'package' || $near->type === 'activity') {
                $nearbyTours->push($near->nearDeal);
            }
        }

        // If no nearby deals, get random packages and activities
        if ($nearbyTours->isEmpty()) {
            $nearbyTours = Deal::whereIn('type', ['package', 'activity'])
                ->where('id', '!=', $tour->id)
                ->with(['category', 'photos'])
                ->limit(3)
                ->get();
        }

        // Limit to reasonable numbers
        $nearbyHotels = $nearbyHotels->take(3);
        $nearbyTours = $nearbyTours->take(6);

        return view('website.pages.view_tour', compact('tour', 'nearbyHotels', 'nearbyTours', 'paginatedReviews', 'hashids'));
    }

    // viewActivity
    public function viewActivity($id)
    {
        $hashids = $this->getHashids();
        
        // Decode the hashed ID
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            abort(404, 'Activity not found');
        }
        $activityId = $decodedIds[0];

        $activity = Deal::where('type', 'activity')
            ->where('id', $activityId)
            ->with(['category', 'photos', 'tours', 'features', 'tourIncludes.feature'])
            ->firstOrFail();

        return view('website.pages.view_activity', compact('activity', 'hashids'));
    }

    // viewPackage
    public function viewPackage($id)
    {
        $hashids = $this->getHashids();
        
        // Decode the hashed ID
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            abort(404, 'Package not found');
        }
        $packageId = $decodedIds[0];

        $package = Deal::where('type', 'package')
            ->where('id', $packageId)
            ->with(['category', 'photos', 'tours', 'features', 'tourIncludes.feature'])
            ->firstOrFail();

        return view('website.pages.view_package', compact('package', 'hashids'));
    }

    /**
     * Handle search by category (URL parameter)
     */
    public function searchByCategory($category)
    {
        $hashids = $this->getHashids();
        
        // Decode category hashid
        $decodedIds = $hashids->decode($category);
        if (empty($decodedIds)) {
            abort(404, 'Category not found');
        }
        $categoryId = $decodedIds[0];

        // Start building the query - STRICTLY filter by category only
        $query = Deal::active()
            ->where('category_id', $categoryId); // Only deals in this specific category

        // Get all matching deals with relationships
        $deals = $query->with(['category', 'photos'])
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get categories for filter dropdown
        $categories = Category::whereIn('type', ['hotel', 'apartment', 'package', 'activity', 'car'])
            ->get();

        // Get locations for filter dropdown (only for this category)
        $locations = Deal::active()
            ->where('category_id', $categoryId)
            ->distinct()
            ->pluck('location')
            ->filter()
            ->sort()
            ->values();

        // Prepare deal data with routes and default images
        $deals->getCollection()->transform(function ($deal) use ($hashids) {
            $deal->view_route = $this->getViewRoute($deal, $hashids);
            $deal->default_image = $this->getDefaultImage($deal->type);
            return $deal;
        });

        return view('website.pages.search_results', [
            'deals' => $deals,
            'categories' => $categories,
            'locations' => $locations,
            'hashids' => $hashids,
            'location' => '',
            'category' => $category,
            'name' => ''
        ]);
    }

    /**
     * Handle search functionality from homepage
     */
    public function search(Request $request)
    {
        $hashids = $this->getHashids();
        $location = $request->get('location');
        $categoryHash = $request->get('category');
        $name = $request->get('name');

        // Decode category hashid if provided
        $category = null;
        if ($categoryHash) {
            $decodedIds = $hashids->decode($categoryHash);
            if (!empty($decodedIds)) {
                $category = $decodedIds[0];
            }
        }

        // Start building the query
        $query = Deal::active();

        // Apply location filter
        if ($location) {
            $query->where('location', 'like', '%' . $location . '%');
        }

        // Apply category filter - STRICTLY filter by category_id
        if ($category) {
            $query->where('category_id', $category); // Only deals in this specific category
        }

        // Apply name/title filter
        if ($name) {
            $query->where('title', 'like', '%' . $name . '%');
        }

        // Get all matching deals with relationships
        $deals = $query->with(['category', 'photos'])
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get categories for filter dropdown
        $categories = Category::whereIn('type', ['hotel', 'apartment', 'package', 'activity', 'car'])
            ->get();

        // Get locations for filter dropdown
        $locations = Deal::active()
            ->distinct()
            ->pluck('location')
            ->filter()
            ->sort()
            ->values();

        $hashids = $this->getHashids();

        // Prepare deal data with routes and default images
        $deals->getCollection()->transform(function ($deal) use ($hashids) {
            $deal->view_route = $this->getViewRoute($deal, $hashids);
            $deal->default_image = $this->getDefaultImage($deal->type);
            return $deal;
        });

        return view('website.pages.search_results', compact(
            'deals',
            'categories',
            'locations',
            'hashids',
            'location',
            'category',
            'name'
        ));
    }

    /**
     * Get the appropriate view route for a deal
     */
    private function getViewRoute($deal, $hashids = null)
    {
        if ($hashids === null) {
            $hashids = $this->getHashids();
        }
        $encodedId = $hashids->encode($deal->id);
        
        switch ($deal->type) {
            case 'hotel':
                return route('view-hotel', ['id' => $encodedId]);
            case 'apartment':
                return route('view-apartment', ['id' => $encodedId]);
            case 'tour':
                return route('view-tour', ['id' => $encodedId]);
            case 'car':
                return route('view-car', ['id' => $encodedId]);
            case 'activity':
                return route('view-activity', ['id' => $encodedId]);
            case 'package':
                return route('view-package', ['id' => $encodedId]);
            default:
                return '#';
        }
    }

    /**
     * Get default image for deal type
     */
    private function getDefaultImage($dealType)
    {
        switch ($dealType) {
            case 'hotel':
                return asset('images/default-hotel.jpg');
            case 'apartment':
                return asset('images/default-apartment.jpg');
            case 'tour':
                return asset('images/default-tour.jpg');
            case 'car':
                return asset('images/default-car.jpg');
            default:
                return asset('images/default-placeholder.jpg');
        }
    }

    // Store Review
    public function storeReview(Request $request, $id)
    {
        
        $dealId = $id;
        $deal = Deal::find($dealId);
        
        if (!$deal) {
            return response()->json(['error' => 'Deal not found'], 404);
        }

        $request->validate([
            'review_title' => 'required|string|max:255',
            'review_content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        try {
            $review = DealReviews::create([
                'deal_id' => $dealId,
                'user_id' => 1, // Set to 1 as requested
                'review_title' => $request->review_title,
                'review_content' => $request->review_content,
                'rating' => $request->rating,
                'is_approved' => true
            ]);

            return redirect()->back()->with('success', 'Review submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Review submission failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to submit review. Please try again.');
        }
    }

    /**
     * Display cart page
     */
    public function cart()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your cart.');
        }

        // Get cart items from database
        $cartItems = \App\Models\BookingItem::where('user_id', Auth::id())
            ->where('status', 'cart')
            ->with(['deal.category', 'deal.photos', 'room'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalAmount = $cartItems->sum('total_price');

        $hashids = $this->getHashids();

        return view('website.pages.cart', compact('cartItems', 'totalAmount', 'hashids'));
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to add items to cart.'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'You must be logged in to add items to cart.');
        }

        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'quantity' => 'required|integer|min:1',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date|after:check_in',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'pickup_location' => 'nullable|string',
            'pickup_time' => 'nullable|string',
            'return_location' => 'nullable|string',
            'return_time' => 'nullable|string',
        ]);

        $deal = Deal::findOrFail($request->deal_id);
        $cart = session('cart', collect());
        
        // Create cart item key based on deal and booking details
        $itemKey = $this->generateCartItemKey($request->all());
        
        // Check if item already exists in cart
        $existingItem = $cart->firstWhere('key', $itemKey);
        
        if ($existingItem) {
            // Update quantity if item exists
            $existingItem['quantity'] += $request->quantity;
            $cart = $cart->map(function ($item) use ($itemKey, $existingItem) {
                return $item['key'] === $itemKey ? $existingItem : $item;
            });
        } else {
            // Add new item to cart
            $cartItem = [
                'key' => $itemKey,
                'user_id' => Auth::id(),
                'deal_id' => $deal->id,
                'quantity' => $request->quantity,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'adults' => $request->adults ?? 1,
                'children' => $request->children ?? 0,
                'pickup_location' => $request->pickup_location ?? null,
                'pickup_time' => $request->pickup_time ?? null,
                'return_location' => $request->return_location ?? null,
                'return_time' => $request->return_time ?? null,
                'added_at' => now()->toISOString(),
            ];
            
            $cart->push($cartItem);
        }

        session(['cart' => $cart]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'cart_count' => $cart->count(),
                'cart_total' => $this->calculateCartTotal($cart)
            ]);
        }

        return redirect()->back()->with('success', 'Item added to cart successfully');
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to modify your cart.'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'You must be logged in to modify your cart.');
        }

        $request->validate([
            'item_key' => 'required|string'
        ]);

        $cart = session('cart', collect());
        $cart = $cart->reject(function ($item) use ($request) {
            return $item['key'] === $request->item_key && $item['user_id'] === Auth::id();
        });

        session(['cart' => $cart]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully',
                'cart_count' => $cart->count(),
                'cart_total' => $this->calculateCartTotal($cart)
            ]);
        }

        return redirect()->back()->with('success', 'Item removed from cart successfully');
    }

    /**
     * Clear entire cart
     */
    public function clearCart(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to modify your cart.'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'You must be logged in to modify your cart.');
        }

        $cart = session('cart', collect());
        // Only clear items belonging to the current user
        $cart = $cart->reject(function ($item) {
            return $item['user_id'] === Auth::id();
        });

        session(['cart' => $cart]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'cart_count' => 0,
                'cart_total' => 0
            ]);
        }

        return redirect()->back()->with('success', 'Cart cleared successfully');
    }

    /**
     * Generate unique cart item key
     */
    private function generateCartItemKey($data)
    {
        $keyData = [
            'user_id' => Auth::id(),
            'deal_id' => $data['deal_id'],
            'check_in' => $data['check_in'] ?? null,
            'check_out' => $data['check_out'] ?? null,
            'adults' => $data['adults'] ?? 1,
            'children' => $data['children'] ?? 0,
            'pickup_location' => $data['pickup_location'] ?? null,
            'pickup_time' => $data['pickup_time'] ?? null,
            'return_location' => $data['return_location'] ?? null,
            'return_time' => $data['return_time'] ?? null,
        ];

        return md5(serialize($keyData));
    }

    /**
     * Calculate cart total
     */
    private function calculateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $deal = Deal::find($item['deal_id']);
            if ($deal) {
                $total += $item['quantity'] * $deal->base_price;
            }
        }
        return $total;
    }

    /**
     * Subscribe to newsletter
     */
    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        try {
            \App\Models\Newsletter::updateOrCreate(
                ['email' => $request->email],
                ['subscribed' => true]
            );

            Log::info('Newsletter subscription', ['email' => $request->email]);
            return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
        } catch (\Exception $e) {
            Log::error('Newsletter subscription failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to subscribe. Please try again.');
        }
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribeNewsletter(Request $request)
    {
        if (!$request->has('email')) {
            return redirect()->route('index')->with('error', 'Invalid unsubscribe link.');
        }

        try {
            $email = base64_decode($request->email);
            
            $newsletter = \App\Models\Newsletter::where('email', $email)->first();
            if ($newsletter) {
                $newsletter->subscribed = false;
                $newsletter->save();
                
                Log::info('Newsletter unsubscription', ['email' => $email]);
                return redirect()->route('index')->with('success', 'You have been unsubscribed from our newsletter.');
            }
            
            return redirect()->route('index')->with('info', 'Email not found in our newsletter list.');
        } catch (\Exception $e) {
            Log::error('Newsletter unsubscription failed: ' . $e->getMessage());
            return redirect()->route('index')->with('error', 'Failed to unsubscribe. Please try again.');
        }
    }

}
