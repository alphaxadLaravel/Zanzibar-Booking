<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Deal;
use App\Models\Room;
use App\Models\Tours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function confirmBooking(Request $request)
    {
        return view('website.pages.confirm_booking');

    }

    public function processBooking(Request $request)
    {
        try {
            Log::info('Processing booking request', $request->all());

            // Validate the request
            $validator = $this->validateBookingRequest($request);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $deal = Deal::with(['tour', 'car'])->findOrFail($request->deal_id);
            
            // Create booking
            $booking = $this->createBooking($request, $deal);
            
            Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'total_price' => $booking->total_price
            ]);

            // Redirect to payment
            return redirect()->route('payment.process', $booking->id);
            
        } catch (\Exception $e) {
            Log::error('Booking processing failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Booking failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function validateBookingRequest(Request $request)
    {
        $rules = [
            'deal_id' => 'required|exists:deals,id',
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'adult' => 'required|integer|min:1',
            'children' => 'integer|min:0',
        ];

        $deal = Deal::find($request->deal_id);
        
        // Add validation rules based on deal type
        if ($deal) {
            switch ($deal->type) {
                case 'hotel':
                case 'apartment':
                    $rules = array_merge($rules, [
                        'room_id' => 'required|exists:rooms,id',
                        'check_in' => 'required|date|after_or_equal:today',
                        'check_out' => 'required|date|after:check_in',
                        'number_rooms' => 'required|integer|min:1'
                    ]);
                    break;
                    
                case 'tour':
                    $rules = array_merge($rules, [
                        'pickup_location' => 'required|string|max:255',
                        'pickup_time' => 'required|date_format:H:i'
                    ]);
                    break;
                    
                case 'car':
                    $rules = array_merge($rules, [
                        'pickup_location' => 'required|string|max:255',
                        'return_location' => 'required|string|max:255',
                        'pickup_time' => 'required|date_format:H:i',
                        'return_time' => 'required|date_format:H:i|after:pickup_time',
                        'need_driver' => 'boolean'
                    ]);
                    break;
            }
        }

        return Validator::make($request->all(), $rules);
    }

    private function createBooking(Request $request, Deal $deal)
    {
        $bookingData = [
            'deal_id' => $deal->id,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'adult' => $request->adult,
            'children' => $request->children ?? 0,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'payment_status' => false
        ];

        // Add deal-specific data
        switch ($deal->type) {
            case 'hotel':
            case 'apartment':
                $bookingData = array_merge($bookingData, [
                    'room_id' => $request->room_id,
                    'check_in' => $request->check_in,
                    'check_out' => $request->check_out,
                    'number_rooms' => $request->number_rooms
                ]);
                break;
                
            case 'tour':
                $bookingData = array_merge($bookingData, [
                    'pickup_location' => $request->pickup_location,
                    'pickup_time' => $request->pickup_time
                ]);
                break;
                
            case 'car':
                $bookingData = array_merge($bookingData, [
                    'pickup_location' => $request->pickup_location,
                    'return_location' => $request->return_location,
                    'pickup_time' => $request->pickup_time,
                    'return_time' => $request->return_time,
                    'need_driver' => $request->has('need_driver')
                ]);
                break;
        }

        // Calculate total price before creating booking
        $tempBooking = new Booking($bookingData);
        $tempBooking->deal = $deal;
        if (isset($bookingData['room_id'])) {
            $tempBooking->room = Room::find($bookingData['room_id']);
        }
        
        $totalPrice = $tempBooking->calculateTotalPrice();
        $bookingData['total_price'] = $totalPrice;
        
        $booking = Booking::create($bookingData);

        return $booking;
    }

    public function viewBooking($bookingId)
    {
        try {
            $booking = Booking::with(['deal', 'room', 'payments'])
                ->findOrFail($bookingId);

            return view('website.pages.booking_details', compact('booking'));
            
        } catch (\Exception $e) {
            Log::error('Failed to view booking', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('index')->with('error', 'Booking not found.');
        }
    }

    public function cancelBooking($bookingId)
    {
        try {
            $booking = Booking::findOrFail($bookingId);
            
            // Only allow cancellation if booking is pending
            if ($booking->status !== 'pending') {
                return redirect()->back()->with('error', 'Cannot cancel this booking.');
            }

            $booking->status = 'cancelled';
            $booking->save();

            Log::info('Booking cancelled', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code
            ]);

            return redirect()->back()->with('success', 'Booking cancelled successfully.');
            
        } catch (\Exception $e) {
            Log::error('Failed to cancel booking', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to cancel booking.');
        }
    }
}
