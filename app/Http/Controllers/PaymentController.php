<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccessUser;
use App\Mail\PaymentSuccessAdmin;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\CurrencyConverter;
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
            // Get and clean Pesapal credentials (remove quotes if present)
            $consumerKey = trim(config('pesapal.consumer_key', ''), " \t\n\r\0\x0B\"'");
            $consumerSecret = trim(config('pesapal.consumer_secret', ''), " \t\n\r\0\x0B\"'");
            $environment = config('pesapal.environment', 'sandbox');
            
            // Log credentials info (without exposing full values)
            Log::info('Pesapal credentials check', [
                'environment' => $environment,
                'consumer_key_length' => strlen($consumerKey),
                'consumer_secret_length' => strlen($consumerSecret),
                'consumer_key_first_5' => substr($consumerKey, 0, 5),
                'consumer_secret_first_5' => substr($consumerSecret, 0, 5),
                'has_quotes_in_key' => strpos($consumerKey, '"') !== false || strpos($consumerKey, "'") !== false,
                'has_quotes_in_secret' => strpos($consumerSecret, '"') !== false || strpos($consumerSecret, "'") !== false
            ]);
            
            if (empty($consumerKey) || empty($consumerSecret)) {
                return redirect()->route('index')->with('error', 'Payment gateway not configured. Please contact support.');
            }
            
            // Update config with cleaned credentials (so Pesapal library gets clean values)
            // IMPORTANT: The knox/pesapal library uses 'live' (boolean) not 'environment'
            $isLive = ($environment === 'live');
            config([
                'pesapal.consumer_key' => $consumerKey,
                'pesapal.consumer_secret' => $consumerSecret,
                'pesapal.callback_route' => 'payment.success',
                'pesapal.environment' => $environment,
                'pesapal.live' => $isLive  // Library uses this boolean
            ]);
            
            // Verify config was updated correctly
            $verifyKey = config('pesapal.consumer_key');
            $verifySecret = config('pesapal.consumer_secret');
            if ($verifyKey !== $consumerKey || $verifySecret !== $consumerSecret) {
                Log::error('Config update failed', [
                    'expected_key' => substr($consumerKey, 0, 10),
                    'actual_key' => substr($verifyKey, 0, 10),
                    'expected_secret' => substr($consumerSecret, 0, 10),
                    'actual_secret' => substr($verifySecret, 0, 10)
                ]);
            }
            
            // Decode booking ID
            $hashids = new \Hashids\Hashids('MchungajiZanzibarBookings', 10);
            $decodedIds = $hashids->decode($bookingId);
            
            if (empty($decodedIds)) {
                return redirect()->route('index')->with('error', 'Invalid booking reference.');
            }
            
            $booking = Booking::findOrFail($decodedIds[0]);
            
            if ($booking->payment_status) {
                return redirect()->route('booking.view', $bookingId)->with('info', 'This booking has already been paid.');
            }

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'status' => 'PENDING',
                'payment_method' => 'PESAPAL',
                'transactionid' => Pesapal::random_reference(),
                'user_id' => $booking->user_id
            ]);

            // Generate callback URLs
            $callbackUrl = url('/payment/success');
            $notificationUrl = url('/payment/confirmation');

            // Send amount in user's currency using our rate so Pesapal shows same amount as booking summary
            $userCurrency = userCurrency();
            $amountUsd = (float) $booking->total_amount;
            if ($userCurrency !== 'USD') {
                $amountToSend = round(CurrencyConverter::convertFromBase($amountUsd, $userCurrency), 2);
                $currencyToSend = $userCurrency;
            } else {
                $amountToSend = $amountUsd;
                $currencyToSend = 'USD';
            }

            // Prepare payment details
            $details = [
                'amount' => $amountToSend,
                'description' => $this->getPaymentDescription($booking),
                'type' => 'MERCHANT',
                'first_name' => $this->getFirstName($booking->fullname),
                'last_name' => $this->getLastName($booking->fullname),
                'email' => $booking->email,
                'phonenumber' => $booking->phone,
                'reference' => $payment->transactionid,
                'currency' => $currencyToSend,
                'callback_url' => $callbackUrl,
                'notification_url' => $notificationUrl
            ];

            // Call Pesapal
            $iframe = Pesapal::makePayment($details);
            
            if (empty($iframe)) {
                throw new \Exception('Pesapal returned empty response. Please check your credentials.');
            }
            
            if (is_string($iframe) && (stripos($iframe, 'error') !== false || stripos($iframe, 'Invalid') !== false)) {
                throw new \Exception('Pesapal error: ' . substr(strip_tags($iframe), 0, 200));
            }
            
            return view('website.pages.pesapal', [
                'iframe' => $iframe,
                'booking' => $booking,
                'payment' => $payment
            ]);
            
        } catch (\Exception $e) {
            Log::error('Payment process failed', [
                'booking_id' => $bookingId ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            
            $errorMsg = $e->getMessage();
            
            // Provide helpful error messages
            if (stripos($errorMsg, 'Invalid consumer') !== false) {
                $env = config('pesapal.environment');
                $errorMsg = "Pesapal credentials are invalid for {$env} environment. ";
                $errorMsg .= "Please verify your PESAPAL_CONSUMER_KEY and PESAPAL_CONSUMER_SECRET in .env file are correct. ";
                $errorMsg .= "Make sure there are NO quotes around the values and they match your Pesapal {$env} account.";
            }
            
            return redirect()->route('index')->with('error', 'Payment processing failed: ' . $errorMsg);
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
