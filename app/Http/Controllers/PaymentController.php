<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccessUser;
use App\Mail\PaymentSuccessAdmin;
use App\Models\Booking;
use App\Models\Payment;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Knox\Pesapal\Facades\Pesapal;

class PaymentController extends Controller
{
    public function processPayment($bookingId)
    {
        try {
            Log::info('Payment process started', ['booking_id' => $bookingId]);
            
            // Validate Pesapal credentials before proceeding
            $consumerKey = config('pesapal.consumer_key');
            $consumerSecret = config('pesapal.consumer_secret');
            
            if (empty($consumerKey) || empty($consumerSecret)) {
                Log::error('Pesapal credentials missing', [
                    'consumer_key_set' => !empty($consumerKey),
                    'consumer_secret_set' => !empty($consumerSecret)
                ]);
                return redirect()->route('index')->with('error', 'Payment gateway configuration error. Please contact support.');
            }
            
            // Decode the hashed booking ID
            $hashids = new \Hashids\Hashids('MchungajiZanzibarBookings', 10);
            $decodedIds = $hashids->decode($bookingId);
            
            if (empty($decodedIds)) {
                Log::error('Invalid booking ID hash', ['booking_id' => $bookingId]);
                return redirect()->route('index')->with('error', 'Invalid booking reference. Please try again.');
            }
            
            $actualBookingId = $decodedIds[0];
            Log::info('Decoded booking ID', ['hashed_id' => $bookingId, 'actual_id' => $actualBookingId]);
            
            // Load booking (booking items are stored as JSON in booking_items column, not as a relationship)
            $booking = Booking::findOrFail($actualBookingId);
            
            // Check if booking is already paid
            if ($booking->payment_status) {
                return redirect()->route('booking.view', $bookingId)
                    ->with('info', 'This booking has already been paid.');
            }

            // Get deal for description
            $deal = $booking->deal;
            
            Log::info('Booking found for payment', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'total_amount' => $booking->total_amount,
                'deal' => $deal ? $deal->title : 'N/A'
            ]);

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'status' => 'PENDING',
                'payment_method' => 'PESAPAL',
                'transactionid' => Pesapal::random_reference(),
                'user_id' => $booking->user_id
            ]);

            Log::info('Payment record created', [
                'payment_id' => $payment->id,
                'reference' => $payment->reference,
                'transaction_id' => $payment->transactionid,
                'amount' => $payment->amount
            ]);

            // Prepare payment details for Pesapal
            // Generate absolute callback URLs with fallback to config values
            try {
                // Get APP_URL first and validate it
                $appUrl = config('app.url');
                if (empty($appUrl) || $appUrl === 'http://localhost' || !filter_var($appUrl, FILTER_VALIDATE_URL)) {
                    Log::error('APP_URL is not set correctly', [
                        'app_url' => $appUrl,
                        'env_app_url' => env('APP_URL')
                    ]);
                    throw new \Exception('APP_URL is not set correctly in .env file. Please set APP_URL to your domain (e.g., https://yourdomain.com)');
                }
                
                // Try to generate from routes first
                $callbackUrl = route('payment.success');
                $notificationUrl = route('payment.confirmation');
                
                // Check if route() returned "N/A" or invalid URL
                if (empty($callbackUrl) || $callbackUrl === 'N/A' || !filter_var($callbackUrl, FILTER_VALIDATE_URL)) {
                    // Use url() helper with path directly
                    $callbackUrl = url('/payment/success');
                    Log::warning('Route helper failed, using url() helper for callback', [
                        'route_result' => route('payment.success'),
                        'fallback_url' => $callbackUrl
                    ]);
                }
                
                if (empty($notificationUrl) || $notificationUrl === 'N/A' || !filter_var($notificationUrl, FILTER_VALIDATE_URL)) {
                    // Use url() helper with path directly
                    $notificationUrl = url('/payment/confirmation');
                    Log::warning('Route helper failed, using url() helper for notification', [
                        'route_result' => route('payment.confirmation'),
                        'fallback_url' => $notificationUrl
                    ]);
                }
                
                // Final validation - ensure we have valid absolute URLs
                if (empty($callbackUrl) || !filter_var($callbackUrl, FILTER_VALIDATE_URL)) {
                    throw new \Exception('Failed to generate valid callback URL. Generated: ' . $callbackUrl);
                }
                if (empty($notificationUrl) || !filter_var($notificationUrl, FILTER_VALIDATE_URL)) {
                    throw new \Exception('Failed to generate valid notification URL. Generated: ' . $notificationUrl);
                }
                
                // Ensure URLs are absolute (should be already, but double-check)
                if (!preg_match('/^https?:\/\//', $callbackUrl)) {
                    $callbackUrl = $appUrl . '/payment/success';
                }
                if (!preg_match('/^https?:\/\//', $notificationUrl)) {
                    $notificationUrl = $appUrl . '/payment/confirmation';
                }
                
            } catch (\Exception $e) {
                Log::error('Failed to generate callback URLs', [
                    'error' => $e->getMessage(),
                    'app_url' => config('app.url'),
                    'env_app_url' => env('APP_URL'),
                    'pesapal_callback' => config('pesapal.callback_url'),
                    'pesapal_notification' => config('pesapal.notification_url'),
                    'trace' => $e->getTraceAsString()
                ]);
                throw new \Exception('Failed to generate payment callback URLs: ' . $e->getMessage() . '. Please check your APP_URL in .env file is set correctly (e.g., APP_URL=https://yourdomain.com)');
            }
            
            Log::info('Callback URLs generated successfully', [
                'callback_url' => $callbackUrl,
                'notification_url' => $notificationUrl,
                'app_url' => config('app.url')
            ]);
            
            // Build payment details array - ensure callback_url is explicitly set as string
            $details = [
                'amount' => (float) $payment->amount,
                'description' => (string) $this->getPaymentDescription($booking),
                'type' => 'MERCHANT',
                'first_name' => (string) $this->getFirstName($booking->fullname),
                'last_name' => (string) $this->getLastName($booking->fullname),
                'email' => (string) $booking->email,
                'phonenumber' => (string) $booking->phone,
                'reference' => (string) $payment->transactionid,
                'currency' => config('pesapal.currency', 'USD'),
                'callback_url' => (string) $callbackUrl, // Explicitly cast to string
                'notification_url' => (string) $notificationUrl // Explicitly cast to string
            ];
            
            // Final validation: ensure callback_url is actually in the array and is a valid URL
            if (!isset($details['callback_url']) || !is_string($details['callback_url']) || empty($details['callback_url'])) {
                Log::error('Callback URL missing after array creation', [
                    'callback_url_in_array' => isset($details['callback_url']) ? $details['callback_url'] : 'NOT SET',
                    'callback_url_type' => isset($details['callback_url']) ? gettype($details['callback_url']) : 'NOT SET',
                    'details' => $details
                ]);
                throw new \Exception('Callback URL is missing from payment details. Generated URL: ' . ($callbackUrl ?? 'NULL'));
            }

            Log::info('Pesapal payment details prepared', $details);
            
            // Debug: Check if callback_url is properly set
            if (empty($details['callback_url']) || $details['callback_url'] === 'N/A') {
                Log::error('Callback URL is empty or N/A before Pesapal call', [
                    'callback_url' => $details['callback_url'] ?? 'NOT SET',
                    'notification_url' => $details['notification_url'] ?? 'NOT SET',
                    'all_details' => $details
                ]);
                throw new \Exception('Callback URL is missing. Please check your APP_URL configuration.');
            }

            // Verify all required fields are present
            $requiredFields = ['amount', 'description', 'type', 'first_name', 'last_name', 'email', 'phonenumber', 'reference', 'currency', 'callback_url', 'notification_url'];
            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (!isset($details[$field]) || empty($details[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                Log::error('Missing required fields for Pesapal payment', [
                    'missing_fields' => $missingFields,
                    'details' => $details
                ]);
                throw new \Exception('Missing required payment fields: ' . implode(', ', $missingFields));
            }

            // Double check callback URLs are still valid before calling Pesapal
            if (empty($details['callback_url']) || !is_string($details['callback_url']) || $details['callback_url'] === 'N/A') {
                Log::error('Callback URL lost or invalid right before Pesapal call', [
                    'callback_url' => $details['callback_url'] ?? 'NULL',
                    'details_keys' => array_keys($details),
                    'all_details' => $details
                ]);
                throw new \Exception('Callback URL is missing or invalid. Generated URL: ' . ($details['callback_url'] ?? 'NULL'));
            }

            // Debug: Log the exact details being sent to Pesapal
            Log::info('About to call Pesapal::makePayment', [
                'callback_url' => $details['callback_url'],
                'notification_url' => $details['notification_url'],
                'callback_url_type' => gettype($details['callback_url']),
                'callback_url_length' => strlen($details['callback_url'] ?? ''),
                'details_keys' => array_keys($details),
                'details_count' => count($details)
            ]);
            
            try {
                // Ensure callback_url is definitely in the array before sending
                if (!array_key_exists('callback_url', $details) || empty($details['callback_url'])) {
                    throw new \Exception('Callback URL missing from details array right before Pesapal call');
                }
                
                // Log what we're sending to Pesapal
                Log::info('Calling Pesapal::makePayment', [
                    'transaction_id' => $payment->transactionid,
                    'amount' => $details['amount'],
                    'callback_url' => $details['callback_url'],
                    'notification_url' => $details['notification_url'],
                    'consumer_key_set' => !empty($consumerKey),
                    'environment' => config('pesapal.environment')
                ]);
                
                $iframe = Pesapal::makePayment($details);
                
                // Log what Pesapal returned
                Log::info('Pesapal::makePayment response', [
                    'iframe_type' => gettype($iframe),
                    'iframe_is_empty' => empty($iframe),
                    'iframe_length' => is_string($iframe) ? strlen($iframe) : 'N/A',
                    'iframe_preview' => is_string($iframe) ? substr($iframe, 0, 200) : 'Not a string',
                    'has_iframe_tag' => is_string($iframe) && stripos($iframe, '<iframe') !== false,
                    'has_error' => is_string($iframe) && (stripos($iframe, 'error') !== false || stripos($iframe, 'N/A') !== false)
                ]);
                
                // Validate iframe response
                if (empty($iframe)) {
                    Log::error('Pesapal returned empty iframe', [
                        'details_sent' => $details,
                        'callback_url' => $details['callback_url'] ?? 'MISSING'
                    ]);
                    throw new \Exception('Pesapal returned an empty response. Please check your Pesapal credentials and configuration.');
                }
                
                // Check for error messages in the response
                if (is_string($iframe) && (stripos($iframe, 'error') !== false || stripos($iframe, 'N/A') !== false || stripos($iframe, 'failed') !== false)) {
                    Log::error('Pesapal returned error in iframe', [
                        'iframe' => is_string($iframe) ? substr($iframe, 0, 1000) : $iframe,
                        'details_sent' => $details,
                        'callback_url' => $details['callback_url'] ?? 'MISSING'
                    ]);
                    throw new \Exception('Pesapal payment initialization failed. Response: ' . (is_string($iframe) ? substr($iframe, 0, 200) : 'Invalid response'));
                }
                
                // Ensure iframe contains an iframe tag
                if (is_string($iframe) && stripos($iframe, '<iframe') === false) {
                    Log::warning('Pesapal response may not contain iframe tag', [
                        'iframe_preview' => substr($iframe, 0, 500),
                        'iframe_length' => strlen($iframe)
                    ]);
                    // Don't throw error - some Pesapal implementations might return different formats
                }
                
                Log::info('Pesapal iframe generated successfully', [
                    'transaction_id' => $payment->transactionid,
                    'iframe_length' => is_string($iframe) ? strlen($iframe) : 'not_string',
                    'callback_url_used' => $details['callback_url'],
                    'iframe_preview' => is_string($iframe) ? substr(strip_tags($iframe), 0, 100) : 'Not a string'
                ]);
                
                // Final check: ensure iframe is set before returning
                if (!isset($iframe) || (is_string($iframe) && empty(trim($iframe)))) {
                    throw new \Exception('Pesapal iframe is empty or not set');
                }
                
            } catch (\Exception $pesapalError) {
                Log::error('Pesapal makePayment exception', [
                    'error' => $pesapalError->getMessage(),
                    'error_code' => $pesapalError->getCode(),
                    'file' => $pesapalError->getFile(),
                    'line' => $pesapalError->getLine(),
                    'trace' => $pesapalError->getTraceAsString(),
                    'details_sent' => $details,
                    'callback_url_value' => $details['callback_url'] ?? 'NOT SET',
                    'callback_url_type' => isset($details['callback_url']) ? gettype($details['callback_url']) : 'NOT SET',
                    'notification_url_value' => $details['notification_url'] ?? 'NOT SET',
                    'app_url' => config('app.url'),
                    'env_app_url' => env('APP_URL'),
                    'route_callback' => route('payment.success'),
                    'route_notification' => route('payment.confirmation')
                ]);
                
                // Provide more helpful error message
                $errorMsg = $pesapalError->getMessage();
                if (stripos($errorMsg, 'callback') !== false || stripos($errorMsg, 'N/A') !== false) {
                    $errorMsg .= ' Please verify your APP_URL is set correctly in .env file and matches your live domain URL.';
                }
                
                throw new \Exception('Pesapal payment error: ' . $errorMsg);
            }
       
            // Ensure iframe is set before returning to view
            if (!isset($iframe)) {
                Log::error('Iframe variable not set after Pesapal call');
                throw new \Exception('Failed to generate payment iframe. Please try again or contact support.');
            }
            
            Log::info('Returning payment view with iframe', [
                'booking_id' => $booking->id,
                'payment_id' => $payment->id,
                'iframe_set' => isset($iframe),
                'iframe_type' => gettype($iframe)
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

                    // Update cart items to 'paid' status
                    if (is_array($booking->booking_items)) {
                        foreach ($booking->booking_items as $item) {
                            if (isset($item['booking_item_id'])) {
                                $bookingItem = \App\Models\BookingItem::find($item['booking_item_id']);
                                if ($bookingItem) {
                                    $bookingItem->update(['status' => 'paid']);
                                    Log::info('Cart item updated to paid', [
                                        'booking_item_id' => $bookingItem->id,
                                        'booking_id' => $booking->id
                                    ]);
                                }
                            }
                        }
                    }

                    // Send payment success emails
                    try {
                        // Email to customer
                        Mail::to($booking->email)->send(new PaymentSuccessUser($booking, $payment));
                        Log::info('Payment success email sent to customer', ['booking_id' => $booking->id]);
                        
                        // Email to admin
                        $adminEmail = env('ADMIN_EMAIL', 'sales-reservations@zanzibarbookings.com');
                        Mail::to($adminEmail)->send(new PaymentSuccessAdmin($booking, $payment));
                        Log::info('Payment success email sent to admin', ['booking_id' => $booking->id]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send payment success emails', ['error' => $e->getMessage(), 'booking_id' => $booking->id]);
                    }
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
        
        if (!$deal) {
            return "Payment for Booking #{$booking->booking_code}";
        }
        
        $description = "Payment for {$deal->title}";
        
        if (isset($deal->type)) {
            switch ($deal->type) {
                case 'hotel':
                case 'apartment':
                    $description .= " - Accommodation";
                    break;
                case 'tour':
                    $description .= " - Tour Package";
                    break;
                case 'activity':
                    $description .= " - Activity";
                    break;
                case 'car':
                    $description .= " - Car Rental";
                    break;
                case 'package':
                    $description .= " - Package Deal";
                    break;
            }
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
