<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Mail\BookingNotificationAdmin;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Deal;
use App\Models\Room;
use App\Models\Tours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Hashids\Hashids;

class BookingController extends Controller
{
    /**
     * Create Hashids instance
     */
    private function getHashids()
    {
        return new Hashids('MchungajiZanzibarBookings', 10);
    }

    public function confirmBooking(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to confirm booking.');
        }

        $cartItems = collect();
        $totalAmount = 0;

        // Check if cart_items parameter is provided
        if ($request->has('cart_items') && !empty($request->get('cart_items'))) {
            $hashids = $this->getHashids();
            $hashedIds = explode(',', $request->get('cart_items'));
            $cartItemIds = [];
            
            // Decode hashed IDs
            foreach ($hashedIds as $hashedId) {
                $decodedIds = $hashids->decode($hashedId);
                if (!empty($decodedIds)) {
                    $cartItemIds[] = $decodedIds[0];
                }
            }
            
            // Get cart items from database
            $cartItems = BookingItem::where('user_id', Auth::id())
                ->where('status', 'cart')
                ->whereIn('id', $cartItemIds)
                ->with(['deal.category', 'deal.photos', 'room'])
                ->get();

            $totalAmount = $cartItems->sum('total_price');
        }

        $hashids = $this->getHashids();
        return view('website.pages.confirm_booking', compact('cartItems', 'totalAmount', 'hashids'));
    }

    /**
     * Process the final booking
     */
    public function processBooking(Request $request)
    {
        DB::beginTransaction();
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'You must be logged in to make a booking.');
            }

            // Validate the request
            $validated = $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'country' => 'required|string|max:100',
                'payment_method' => 'required|in:pesapal,pay_offline',
                'special_requests' => 'nullable|string|max:1000',
                'selected_items' => 'required|string'
            ]);

            // Get selected cart item IDs (decode from hashids)
            $hashids = $this->getHashids();
            $hashedIds = explode(',', $validated['selected_items']);
            $selectedItemIds = [];
            
            // Decode hashed IDs
            foreach ($hashedIds as $hashedId) {
                $decodedIds = $hashids->decode($hashedId);
                if (!empty($decodedIds)) {
                    $selectedItemIds[] = $decodedIds[0];
                }
            }
            
            if (empty($selectedItemIds)) {
                return back()->withErrors(['selected_items' => 'Please select at least one item to book.'])->withInput();
            }

            // Get cart items from database
            $cartItems = BookingItem::where('user_id', Auth::id())
                ->where('status', 'cart')
                ->whereIn('id', $selectedItemIds)
                ->with(['deal', 'room'])
                ->get();

            if ($cartItems->isEmpty()) {
                return back()->withErrors(['selected_items' => 'No valid items found to book.'])->withInput();
            }

            // Validate that all cart items belong to the current user
            foreach ($cartItems as $item) {
                if ($item->user_id !== Auth::id()) {
                    return back()->withErrors(['selected_items' => 'Unauthorized access to booking items.'])->withInput();
                }
            }

            $bookedItems = [];
            $totalAmount = 0;

            // Prepare booking items data
            foreach ($cartItems as $cartItem) {
                // Update status to pending
                $cartItem->update(['status' => 'pending']);

                $bookedItems[] = [
                    'deal_id' => $cartItem->deal_id,
                    'room_id' => $cartItem->room_id,
                    'number_rooms' => ($cartItem->type === 'hotel') ? $cartItem->number_rooms : null,
                    'type' => $cartItem->type,
                    'check_in' => $cartItem->check_in,
                    'check_out' => $cartItem->check_out,
                    'total_price' => $cartItem->total_price,
                    'adults' => $cartItem->adults,
                    'children' => $cartItem->children,
                    'booking_item_id' => $cartItem->id,
                ];

                $totalAmount += $cartItem->total_price;
            }

            // Create the main booking record
            $booking = Booking::create([
                'booking_code' => Booking::generateBookingCode(),
                'fullname' => $validated['fullname'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'country' => $validated['country'],
                'user_id' => Auth::id(),
                'booking_items' => $bookedItems,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'additional_notes' => $validated['special_requests'] ?? null,
            ]);

            // Log successful booking creation
            Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'items_count' => count($bookedItems)
            ]);

            // Send booking confirmation emails
            try {
                // Email to customer
                Mail::to($booking->email)->send(new BookingConfirmation($booking));
                Log::info('Booking confirmation email sent to customer', ['booking_id' => $booking->id]);
                
                // Email to admin
                $adminEmail = env('ADMIN_EMAIL', 'sales-reservations@zanzibarbookings.com');
                Mail::to($adminEmail)->send(new BookingNotificationAdmin($booking));
                Log::info('Booking notification email sent to admin', ['booking_id' => $booking->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send booking emails', ['error' => $e->getMessage(), 'booking_id' => $booking->id]);
            }

            DB::commit();

            // Redirect based on payment method
            if ($validated['payment_method'] === 'pesapal') {
                return redirect()->route('payment.process', ['bookingId' => $hashids->encode($booking->id)])
                    ->with('success', 'Booking created successfully! Please complete your payment.');
            } else {
                return redirect()->route('offline.payment', ['bookingId' => $hashids->encode($booking->id)])
                    ->with('success', 'Booking created successfully! Please follow the payment instructions below.');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking processing error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while processing your booking. Please try again.'])->withInput();
        }
    }

    /**
     * Show offline payment page
     */
    public function offlinePayment($bookingId)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view payment details.');
        }

        $hashids = $this->getHashids();
        
        // Decode the hashed booking ID
        $decodedIds = $hashids->decode($bookingId);
        if (empty($decodedIds)) {
            abort(404, 'Booking not found');
        }
        $bookingId = $decodedIds[0];
        
        // Get the booking
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->with(['user'])
            ->firstOrFail();

        return view('website.pages.offline_payment', compact('booking'));
    }

    // book room
    public function bookRoom(Request $request)
    {
        DB::beginTransaction();
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return back()->withErrors(['error' => 'You must be logged in to make a booking. Please login first.'])->withInput();
            }

            // Validate the request
            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'deal_id' => 'required|exists:deals,id',
                'check_in' => 'required|date|after_or_equal:today',
                'check_out' => 'required|date|after:check_in',
                'number_of_rooms' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'adults' => 'required|integer|min:1',
                'children' => 'nullable|integer|min:0',
            ]);


            // Get the room and deal
            $room = Room::findOrFail($validated['room_id']);
            $deal = Deal::findOrFail($validated['deal_id']);

            // Verify the room belongs to the deal
            if ($room->deal_id !== $deal->id) {
                DB::rollBack();
                return back()->withErrors(['room_id' => 'The selected room does not belong to this hotel.'])->withInput();
            }

            // Check room availability (basic check)
            $checkIn = \Carbon\Carbon::parse($validated['check_in']);
            $checkOut = \Carbon\Carbon::parse($validated['check_out']);
            $nights = $checkIn->diffInDays($checkOut);

            // Create booking item in database
            $bookingItem = BookingItem::create([
                'user_id' => Auth::id(),
                'deal_id' => $deal->id,
                'room_id' => $room->id,
                'number_rooms' => $validated['number_of_rooms'],
                'type' => 'hotel',
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'total_price' => $validated['price'],
                'adults' => $validated['adults'],
                'children' => $validated['children'] ?? 0,
                'status' => 'cart',
            ]);

            if ($request->has('book_room')) {
                DB::commit();
                $hashids = $this->getHashids();
                return redirect()->route('confirm-booking', ['cart_items' => $hashids->encode($bookingItem->id)])->with('success', 'Room booking details saved. Please complete your booking information.');
                
            } elseif ($request->has('add_cart')) {
                // ADD TO CART button clicked - item is already saved with cart status
                DB::commit();
                return redirect()->back()->with('success', 'Room added to cart successfully!');
            }

            DB::rollBack();
            return redirect()->back()->with('error', 'Invalid action. Please try again.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while processing your booking. Please try again.'])->withInput();
        }
    }

    /**
     * Generate unique cart item key
     */
    private function generateCartItemKey($data)
    {
        $keyData = [
            'user_id' => $data['user_id'] ?? null,
            'deal_id' => $data['deal_id'],
            'room_id' => $data['room_id'] ?? null,
            'check_in' => $data['check_in'] ?? null,
            'check_out' => $data['check_out'] ?? null,
            'adults' => $data['adults'] ?? null,
            'children' => $data['children'] ?? null,
        ];
        
        return md5(serialize($keyData));
    }

    /**
     * Book all items in cart at once
     */
    public function bookAllCart(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to make bookings.'
                ], 401);
            }

            $cartItemIds = $request->input('cart_item_ids', []);
            
            if (empty($cartItemIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items found in cart.'
                ], 400);
            }

            // Get cart items from database
            $cartItems = BookingItem::where('user_id', Auth::id())
                ->where('status', 'cart')
                ->whereIn('id', $cartItemIds)
                ->with(['deal', 'room'])
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid items found to book.'
                ], 400);
            }

            $bookedItems = [];
            $totalAmount = 0;

            DB::beginTransaction();

            foreach ($cartItems as $cartItem) {
                // Update status to pending
                $cartItem->update(['status' => 'pending']);

                $bookedItems[] = [
                    'deal_id' => $cartItem->deal_id,
                    'room_id' => $cartItem->room_id,
                    'number_rooms' => $cartItem->number_rooms,
                    'type' => $cartItem->type,
                    'check_in' => $cartItem->check_in,
                    'check_out' => $cartItem->check_out,
                    'total_price' => $cartItem->total_price,
                    'adults' => $cartItem->adults,
                    'children' => $cartItem->children,
                    'booking_item_id' => $cartItem->id,
                ];

                $totalAmount += $cartItem->total_price;
            }

            // Create the main booking record
            $booking = Booking::create([
                'booking_code' => Booking::generateBookingCode(),
                'fullname' => Auth::user()->name ?? 'Guest',
                'email' => Auth::user()->email ?? 'guest@example.com',
                'phone' => Auth::user()->phone ?? 'N/A',
                'country' => Auth::user()->country ?? 'N/A',
                'user_id' => Auth::id(),
                'booking_items' => $bookedItems,
                'total_amount' => $totalAmount,
                'payment_method' => 'pending',
                'status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All deals have been booked successfully!',
                'booking_code' => $booking->booking_code,
                'total_amount' => $totalAmount,
                'items_count' => count($bookedItems)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Book all cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while booking items. Please try again.'
            ], 500);
        }
    }

    /**
     * Get cart items for the authenticated user
     */
    public function getCartItems()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to view cart.'
            ], 401);
        }

        $cartItems = BookingItem::where('user_id', Auth::id())
            ->where('status', 'cart')
            ->with(['deal', 'room'])
            ->get();

        return response()->json([
            'success' => true,
            'cart_items' => $cartItems,
            'total_amount' => $cartItems->sum('total_price'),
            'items_count' => $cartItems->count()
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to modify cart.'
            ], 401);
        }

        $itemId = $request->input('item_id');
        
        $cartItem = BookingItem::where('id', $itemId)
            ->where('user_id', Auth::id())
            ->where('status', 'cart')
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.'
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully.'
        ]);
    }

    /**
     * Show the booking lookup form
     */
    public function bookingLookup()
    {
        return view('website.pages.booking-lookup');
    }

    /**
     * Process the booking lookup request
     */
    public function processBookingLookup(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|string|max:20'
        ]);

        try {
            // Find booking by code
            $booking = Booking::where('booking_code', $request->booking_code)->first();

            if (!$booking) {
                return back()->withErrors(['booking_code' => 'No booking found with this code. Please check your booking code and try again.'])->withInput();
            }

            // Get booking items with deal details
            $bookingItems = [];
            if ($booking->booking_items) {
                foreach ($booking->booking_items as $item) {
                    $deal = null;
                    $room = null;
                    
                    if (isset($item['deal_id'])) {
                        $deal = Deal::find($item['deal_id']);
                    }
                    
                    if (isset($item['room_id'])) {
                        $room = Room::find($item['room_id']);
                    }

                    $bookingItems[] = [
                        'deal' => $deal,
                        'room' => $room,
                        'item_data' => $item
                    ];
                }
            }

            return view('website.pages.booking-details', compact('booking', 'bookingItems'));

        } catch (\Exception $e) {
            Log::error('Booking lookup error: ' . $e->getMessage(), [
                'booking_code' => $request->booking_code,
                'user_ip' => $request->ip()
            ]);

            return back()->withErrors(['booking_code' => 'An error occurred while looking up your booking. Please try again later.'])->withInput();
        }
    }

    /**
     * Book package/activity/car
     */
    public function bookDeal(Request $request)
    {
        DB::beginTransaction();
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return back()->withErrors(['error' => 'You must be logged in to make a booking. Please login first.'])->withInput();
            }

            // Validate the request based on deal type
            $validated = $this->validateDealBooking($request);
            
            // Ensure children is an integer
            if (isset($validated['children'])) {
                $validated['children'] = (int) $validated['children'];
            }

            // Get the deal with required relationships
            $deal = Deal::with(['tours', 'car'])->findOrFail($validated['deal_id']);
            
            // For activities/packages, ensure tours relationship exists
            if (in_array($validated['type'], ['activity', 'package']) && !$deal->tours) {
                throw new \Exception('Activity/package pricing information is missing. The activity does not have pricing data configured.');
            }

            // Calculate total price based on deal type
            $totalPrice = $this->calculateDealPrice($deal, $validated);

            // Create booking item in database
            $bookingItem = BookingItem::create([
                'user_id' => Auth::id(),
                'deal_id' => $deal->id,
                'room_id' => $validated['room_id'] ?? null,
                'number_rooms' => ($validated['type'] === 'hotel') ? ($validated['number_rooms'] ?? 1) : null,
                'type' => $validated['type'],
                'check_in' => $validated['check_in'] ?? $validated['pickup_date'] ?? null,
                'check_out' => $validated['check_out'] ?? $validated['return_date'] ?? null,
                'total_price' => $totalPrice,
                'adults' => $validated['adults'] ?? 1,
                'children' => $validated['children'] ?? 0,
                'status' => 'cart',
            ]);

            if ($request->has('book_now')) {
                DB::commit();
                $hashids = $this->getHashids();
                return redirect()->route('confirm-booking', ['cart_items' => $hashids->encode($bookingItem->id)])->with('success', 'Booking details saved. Please complete your booking information.');
                
            } elseif ($request->has('add_cart')) {
                // ADD TO CART button clicked - item is already saved with cart status
                DB::commit();
                return redirect()->back()->with('success', 'Item added to cart successfully!');
            }

            DB::rollBack();
            return redirect()->back()->with('error', 'Invalid action. Please try again.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Deal booking error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'user_id' => Auth::id(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // For admin users, show more detailed error for debugging
            $user = Auth::user();
            $isAdmin = $user && optional($user->role)->name === 'Admin';
            
            if ($isAdmin) {
                $errorMessage = 'Error: ' . $e->getMessage() . ' (File: ' . basename($e->getFile()) . ', Line: ' . $e->getLine() . ')';
            } else {
                // Don't expose detailed error to regular users
                $errorMessage = 'An error occurred while processing your booking. Please try again.';
                
                // Check for specific error types
                if (str_contains($e->getMessage(), 'tours') || str_contains($e->getMessage(), 'relationship')) {
                    $errorMessage = 'Booking details are incomplete. Please contact support.';
                }
            }
            
            return back()->withErrors(['error' => $errorMessage])->withInput();
        }
    }

    /**
     * Validate deal booking request based on type
     */
    private function validateDealBooking(Request $request)
    {
        $type = $request->input('type');
        
        switch ($type) {
            case 'package':
            case 'activity':
                return $request->validate([
                    'deal_id' => 'required|exists:deals,id',
                    'type' => 'required|in:package,activity',
                    'check_in' => 'required|date|after_or_equal:today',
                    'adults' => 'required|integer|min:1',
                    'children' => 'nullable|integer|min:0',
                ]);
                
            case 'apartment':
                return $request->validate([
                    'deal_id' => 'required|exists:deals,id',
                    'type' => 'required|in:apartment',
                    'check_in' => 'required|date|after_or_equal:today',
                    'check_out' => 'required|date|after:check_in',
                    'adults' => 'required|integer|min:1',
                    'children' => 'nullable|integer|min:0',
                ]);
                
            case 'car':
                return $request->validate([
                    'deal_id' => 'required|exists:deals,id',
                    'type' => 'required|in:car',
                    'pickup_date' => 'required|date|after_or_equal:today',
                    'return_date' => 'required|date|after:pickup_date',
                ]);
                
            default:
                throw new \InvalidArgumentException('Invalid deal type');
        }
    }

    /**
     * Calculate deal price based on type and parameters
     */
    private function calculateDealPrice($deal, $validated)
    {
        switch ($validated['type']) {
            case 'package':
            case 'activity':
                // Check if tours relationship exists
                if (!$deal->tours) {
                    throw new \Exception('Activity/package pricing information is missing. Please contact support.');
                }
                
                $adultPrice = $deal->tours->adult_price ?? 0;
                $childPrice = $deal->tours->child_price ?? 0;
                
                if ($adultPrice <= 0) {
                    throw new \Exception('Adult price is not set for this activity/package.');
                }
                
                $adults = $validated['adults'] ?? 1;
                $children = $validated['children'] ?? 0;
                return ($adults * $adultPrice) + ($children * $childPrice);
                
            case 'apartment':
                $checkInDate = \Carbon\Carbon::parse($validated['check_in']);
                $checkOutDate = \Carbon\Carbon::parse($validated['check_out']);
                $nights = $checkInDate->diffInDays($checkOutDate);
                return $deal->base_price * max(1, $nights);
                
            case 'car':
                $pickupDate = \Carbon\Carbon::parse($validated['pickup_date']);
                $returnDate = \Carbon\Carbon::parse($validated['return_date']);
                $days = $pickupDate->diffInDays($returnDate);
                return $deal->base_price * max(1, $days);
                
            default:
                return $deal->base_price;
        }
    }

  
}
