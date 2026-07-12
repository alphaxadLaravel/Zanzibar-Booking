<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Deal;
use App\Models\FlightBooking;
use App\Models\Payment;
use App\Models\System;
use App\Models\Tours;
use App\Services\CurrencyConverter;
use App\Services\GroupPackageCapacityService;
use App\Support\HashidsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Knox\Pesapal\Facades\Pesapal;

class PaymentApiController extends Controller
{
    public const DEEP_LINK_SCHEME = 'zanzibarbookings://payment/success';

    public function initiatePesapal(Request $request, string $bookingId)
    {
        $id = HashidsHelper::resolveId($bookingId) ?? (is_numeric($bookingId) ? (int) $bookingId : null);
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        if ($booking->payment_status) {
            return response()->json(['message' => 'This booking has already been paid.'], 422);
        }

        $capacityService = app(GroupPackageCapacityService::class);
        if (is_array($booking->booking_items)) {
            foreach ($booking->booking_items as $item) {
                if (!isset($item['deal_id'])) {
                    continue;
                }
                $tour = Tours::where('deal_id', $item['deal_id'])->first();
                if (!$tour || !$tour->is_group_package) {
                    continue;
                }
                $participants = ($item['adults'] ?? 0) + ($item['children'] ?? 0);
                $check = $capacityService->canBook($tour, $participants);
                if (!$check['allowed']) {
                    return response()->json(['message' => $check['message']], 422);
                }
            }
        }

        try {
            $this->configurePesapal();

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'status' => 'PENDING',
                'payment_method' => 'PESAPAL',
                'transactionid' => Pesapal::random_reference(),
                'user_id' => $booking->user_id,
            ]);

            $callbackUrl = url('/api/v1/payments/mobile-callback');
            $notificationUrl = url('/payment/confirmation');

            $userCurrency = function_exists('userCurrency') ? userCurrency() : 'USD';
            $amountUsd = (float) $booking->total_amount;
            if ($userCurrency !== 'USD') {
                $amountToSend = round(CurrencyConverter::convertFromBase($amountUsd, $userCurrency), 2);
                $currencyToSend = $userCurrency;
            } else {
                $amountToSend = $amountUsd;
                $currencyToSend = 'USD';
            }

            $parts = preg_split('/\s+/', trim($booking->fullname), 2);
            $details = [
                'amount' => $amountToSend,
                'description' => 'Zanzibar Bookings — ' . $booking->booking_code,
                'type' => 'MERCHANT',
                'first_name' => $parts[0] ?? 'Guest',
                'last_name' => $parts[1] ?? 'Traveler',
                'email' => $booking->email,
                'phonenumber' => $booking->phone,
                'reference' => $payment->transactionid,
                'currency' => $currencyToSend,
                'callback_url' => $callbackUrl,
                'notification_url' => $notificationUrl,
            ];

            $iframe = Pesapal::makePayment($details);

            if (empty($iframe) || (is_string($iframe) && (stripos($iframe, 'error') !== false || stripos($iframe, 'Invalid') !== false))) {
                throw new \RuntimeException('Pesapal could not start payment. Please try again.');
            }

            $wrappedHtml = self::wrapIframeHtml($iframe);

            return response()->json([
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transactionid,
                'iframe_html' => $wrappedHtml,
                'callback_scheme' => self::DEEP_LINK_SCHEME,
                'booking' => [
                    'id' => $booking->id,
                    'hashid' => HashidsHelper::encode($booking->id),
                    'booking_code' => $booking->booking_code,
                    'total_amount' => (float) $booking->total_amount,
                    'items' => $this->bookingSummaryItems($booking),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('API Pesapal init failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function offline(Request $request, string $bookingId)
    {
        $id = HashidsHelper::resolveId($bookingId) ?? (is_numeric($bookingId) ? (int) $bookingId : null);
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);
        $system = System::query()->first();

        return response()->json([
            'booking' => [
                'id' => $booking->id,
                'hashid' => HashidsHelper::encode($booking->id),
                'booking_code' => $booking->booking_code,
                'total_amount' => (float) $booking->total_amount,
                'status' => $booking->status,
                'items' => $this->bookingSummaryItems($booking),
            ],
            'instructions' => [
                'title' => 'Bank / Offline Payment',
                'message' => 'Please complete your bank transfer and include your booking code as the payment reference. Our team will confirm your booking once payment is received.',
                'booking_code' => $booking->booking_code,
                'contact_email' => $system?->email ?? 'sales-reservations@zanzibarbookings.com',
                'contact_phone' => $system?->phone,
            ],
        ]);
    }

    public function status(Request $request, string $paymentId)
    {
        $payment = Payment::where('id', $paymentId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $booking = $payment->booking_id ? Booking::find($payment->booking_id) : null;
        $flight = $payment->flight_booking_id ? FlightBooking::find($payment->flight_booking_id) : null;

        return response()->json([
            'payment_id' => $payment->id,
            'status' => $payment->status,
            'amount' => (float) $payment->amount,
            'transaction_id' => $payment->transactionid,
            'tracking_id' => $payment->trackingid,
            'booking_paid' => (bool) ($booking?->payment_status),
            'booking_code' => $booking?->booking_code,
            'flight_status' => $flight?->status,
            'flight_reference' => $flight?->booking_reference,
        ]);
    }

    /**
     * Pesapal redirects here after payment; we bounce into the app deep link.
     */
    public function mobileCallback(Request $request)
    {
        $tracking = $request->input('pesapal_transaction_tracking_id', '');
        $ref = $request->input('pesapal_merchant_reference', '');

        if ($ref) {
            $payment = Payment::where('transactionid', $ref)->first();
            if ($payment && $tracking) {
                $payment->trackingid = $tracking;
                if ($payment->status === 'PENDING' || empty($payment->status)) {
                    $payment->status = 'PENDING';
                }
                $payment->save();
            }
        }

        $deepLink = self::DEEP_LINK_SCHEME . '?' . http_build_query([
            'ref' => $ref,
            'tracking' => $tracking,
        ]);
        $deepLinkJs = json_encode($deepLink, JSON_UNESCAPED_SLASHES);
        $deepLinkAttr = htmlspecialchars($deepLink, ENT_QUOTES, 'UTF-8');

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Payment complete</title>
  <style>
    body{font-family:system-ui,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#0f766e;color:#fff;text-align:center;padding:24px}
    a{color:#fff;font-weight:600}
  </style>
</head>
<body>
  <div>
    <h1>Payment received</h1>
    <p>Returning you to Zanzibar Bookings…</p>
    <p><a href="{$deepLinkAttr}">Open app</a></p>
  </div>
  <script>window.location.replace({$deepLinkJs});</script>
</body>
</html>
HTML;

        return response($html, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public static function wrapIframeHtml(string $iframe): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>Secure Payment</title>
  <style>
    html,body{margin:0;padding:0;height:100%;background:#f8fafc}
    #pesapal-payment-form,iframe{width:100%!important;min-height:100vh!important;border:0!important}
  </style>
</head>
<body>
{$iframe}
</body>
</html>
HTML;
    }

    /** Build a pay-summary list from stored booking_items (enrich older bookings). */
    protected function bookingSummaryItems(Booking $booking): array
    {
        $raw = is_array($booking->booking_items) ? $booking->booking_items : [];
        $dealIds = collect($raw)->pluck('deal_id')->filter()->unique()->values()->all();
        $deals = $dealIds === []
            ? collect()
            : Deal::query()->whereIn('id', $dealIds)->get()->keyBy('id');

        return collect($raw)->map(function ($item) use ($deals) {
            $item = is_array($item) ? $item : (array) $item;
            $deal = isset($item['deal_id']) ? $deals->get($item['deal_id']) : null;
            $cover = $item['cover_photo'] ?? null;
            if (!$cover && $deal?->cover_photo) {
                $cover = str_starts_with((string) $deal->cover_photo, 'http')
                    ? $deal->cover_photo
                    : asset('storage/' . ltrim((string) $deal->cover_photo, '/'));
            }

            return [
                'title' => $item['title'] ?? $deal?->title ?? ucfirst((string) ($item['type'] ?? 'Item')),
                'type' => $item['type'] ?? null,
                'location' => $item['location'] ?? $deal?->location,
                'cover_photo' => $cover,
                'room_name' => $item['room_name'] ?? null,
                'check_in' => $item['check_in'] ?? null,
                'check_out' => $item['check_out'] ?? null,
                'adults' => $item['adults'] ?? null,
                'children' => $item['children'] ?? null,
                'total_price' => (float) ($item['total_price'] ?? 0),
            ];
        })->values()->all();
    }

    protected function configurePesapal(): void
    {
        $consumerKey = trim(config('pesapal.consumer_key', ''), " \t\n\r\0\x0B\"'");
        $consumerSecret = trim(config('pesapal.consumer_secret', ''), " \t\n\r\0\x0B\"'");
        $environment = config('pesapal.environment', 'sandbox');

        if (empty($consumerKey) || empty($consumerSecret)) {
            throw new \RuntimeException('Payment gateway is not configured.');
        }

        config([
            'pesapal.consumer_key' => $consumerKey,
            'pesapal.consumer_secret' => $consumerSecret,
            'pesapal.callback_route' => 'payment.success',
            'pesapal.environment' => $environment,
            'pesapal.live' => $environment === 'live',
        ]);
    }
}
