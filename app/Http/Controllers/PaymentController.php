<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Knox\Pesapal\Facades\Pesapal;

class PaymentController extends Controller
{
    public function processPayment($bookingId)
    {
        try {
            Log::info('Payment process started', ['booking_id' => $bookingId]);
            
            $booking = Booking::with(['deal', 'room'])->findOrFail($bookingId);
            
            // Check if booking is already paid
            if ($booking->payment_status) {
                return redirect()->route('booking.view', $bookingId)
                    ->with('info', 'This booking has already been paid.');
            }

            Log::info('Booking found for payment', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'total_price' => $booking->total_price,
                'deal_type' => $booking->deal->type
            ]);

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_price,
                'status' => 'pending',
                'payment_method' => 'PESAPAL',
                'transactionid' => Pesapal::random_reference()
            ]);

            Log::info('Payment record created', [
                'payment_id' => $payment->id,
                'reference' => $payment->reference,
                'transaction_id' => $payment->transactionid,
                'amount' => $payment->amount
            ]);

            // Prepare payment details for Pesapal
            $details = [
                'amount' => $payment->amount,
                'description' => $this->getPaymentDescription($booking),
                'type' => 'MERCHANT',
                'first_name' => $this->getFirstName($booking->fullname),
                'last_name' => $this->getLastName($booking->fullname),
                'email' => $booking->email,
                'phonenumber' => $booking->phone,
                'reference' => $payment->transactionid,
                'currency' => config('pesapal.currency', 'USD'),
                'callback_url' => route('payment.success'),
                'notification_url' => route('payment.confirmation')
            ];

            Log::info('Pesapal payment details prepared', $details);

            $iframe = Pesapal::makePayment($details);
            
            Log::info('Pesapal iframe generated successfully', [
                'transaction_id' => $payment->transactionid
            ]);
       
            return view('website.pages.pesapal', [
                'iframe' => $iframe,
                'booking' => $booking,
                'payment' => $payment
            ]);
            
        } catch (\Exception $e) {
            Log::error('Payment process failed', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('index')->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    public function paymentSuccess(Request $request)
    {
        try {
            Log::info('Payment success callback received', [
                'method' => $request->method(),
                'pesapal_transaction_tracking_id' => $request->input('pesapal_transaction_tracking_id'),
                'pesapal_merchant_reference' => $request->input('pesapal_merchant_reference'),
                'all_params' => $request->all(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $trackingid = $request->input('pesapal_transaction_tracking_id');
            $ref = $request->input('pesapal_merchant_reference');

            if (!$trackingid || !$ref) {
                Log::error('Missing required parameters in payment success callback', [
                    'tracking_id' => $trackingid,
                    'merchant_reference' => $ref
                ]);
                return redirect()->route('index')->with('error', 'Invalid payment callback parameters.');
            }

            $payment = Payment::where('transactionid', $ref)->first();
            
            if (!$payment) {
                Log::error('Payment record not found for merchant reference', [
                    'merchant_reference' => $ref
                ]);
                return redirect()->route('index')->with('error', 'Payment record not found.');
            }

            $payment->trackingid = $trackingid;
            $payment->status = 'PENDING';
            $payment->save();

            Log::info('Payment status updated to PENDING', [
                'payment_id' => $payment->id,
                'tracking_id' => $trackingid,
                'booking_id' => $payment->booking_id
            ]);

            // Get booking for display
            $booking = Booking::with(['deal'])->find($payment->booking_id);

            return view('website.pages.payment_success', [
                'booking' => $booking,
                'payment' => $payment
            ]);
            
        } catch (\Exception $e) {
            Log::error('Payment success callback failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->route('index')->with('error', 'Payment confirmation failed. Please contact support.');
        }
    }

    public function paymentConfirmation(Request $request)
    {
        try {
            Log::info('Payment confirmation callback received', [
                'method' => $request->method(),
                'pesapal_transaction_tracking_id' => $request->input('pesapal_transaction_tracking_id'),
                'pesapal_merchant_reference' => $request->input('pesapal_merchant_reference'),
                'pesapal_notification_type' => $request->input('pesapal_notification_type'),
                'all_params' => $request->all(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'headers' => $request->headers->all()
            ]);

            $trackingid = $request->input('pesapal_transaction_tracking_id');
            $merchant_reference = $request->input('pesapal_merchant_reference');
            $pesapal_notification_type = $request->input('pesapal_notification_type');

            if (!$trackingid || !$merchant_reference) {
                Log::error('Missing required parameters in payment confirmation callback', [
                    'tracking_id' => $trackingid,
                    'merchant_reference' => $merchant_reference
                ]);
                return response()->json(['error' => 'Missing required parameters'], 400);
            }

            // Only process CHANGE notifications
            if ($pesapal_notification_type == 'CHANGE' && $trackingid) {
                $result = $this->checkPaymentStatus($trackingid, $merchant_reference, $pesapal_notification_type);
                
                Log::info('Payment confirmation processed successfully', [
                    'tracking_id' => $trackingid,
                    'merchant_reference' => $merchant_reference,
                    'result' => $result
                ]);
                
                return response()->json(['status' => 'success', 'result' => $result]);
            }

            return response()->json(['status' => 'ignored', 'message' => 'Notification type not processed']);
            
        } catch (\Exception $e) {
            Log::error('Payment confirmation callback failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json(['error' => 'Payment confirmation failed'], 500);
        }
    }

    public function checkPaymentStatus($trackingid, $merchant_reference, $pesapal_notification_type)
    {
        try {
            Log::info('Checking payment status', [
                'tracking_id' => $trackingid,
                'merchant_reference' => $merchant_reference,
                'notification_type' => $pesapal_notification_type
            ]);

            $status = Pesapal::getMerchantStatus($merchant_reference);
            
            Log::info('Pesapal status retrieved', [
                'merchant_reference' => $merchant_reference,
                'status' => $status
            ]);

            $payment = Payment::where('trackingid', $trackingid)->first();
            
            if (!$payment) {
                Log::error('Payment record not found for tracking ID', [
                    'tracking_id' => $trackingid
                ]);
                return "error";
            }

            $oldStatus = $payment->status;
            $payment->status = $status;
            $payment->save();

            Log::info('Payment status updated', [
                'payment_id' => $payment->id,
                'old_status' => $oldStatus,
                'new_status' => $status,
                'booking_id' => $payment->booking_id
            ]);

            // Update booking payment status if payment is confirmed
            if ($status === 'COMPLETED') {
                $booking = Booking::find($payment->booking_id);
                if ($booking) {
                    $booking->payment_status = true;
                    $booking->status = 'confirmed';
                    $booking->save();
                    
                    Log::info('Booking payment status updated to paid', [
                        'booking_id' => $booking->id,
                        'booking_code' => $booking->booking_code
                    ]);
                }
            }

            return "success";
            
        } catch (\Exception $e) {
            Log::error('Payment status check failed', [
                'tracking_id' => $trackingid,
                'merchant_reference' => $merchant_reference,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return "error";
        }
    }

    private function getPaymentDescription(Booking $booking)
    {
        $deal = $booking->deal;
        $description = "Payment for {$deal->title}";
        
        switch ($deal->type) {
            case 'hotel':
            case 'apartment':
                if ($booking->room) {
                    $description .= " - {$booking->room->title}";
                }
                break;
            case 'tour':
                $description .= " - Tour Package";
                break;
            case 'car':
                $description .= " - Car Rental";
                break;
        }
        
        return $description;
    }

    private function getFirstName($fullname)
    {
        $parts = explode(' ', trim($fullname));
        return $parts[0] ?? '';
    }

    private function getLastName($fullname)
    {
        $parts = explode(' ', trim($fullname));
        return count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
    }
}
