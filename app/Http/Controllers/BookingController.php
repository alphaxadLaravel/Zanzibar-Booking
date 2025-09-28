<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Deal;
use App\Models\Room;
use App\Models\Tours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function confirmBooking(Request $request)
    {
        return view('website.pages.confirm_booking');
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
                return redirect()->route('confirm-booking')->with('success', 'Room booking details saved. Please complete your booking information.');
                
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

  
}
