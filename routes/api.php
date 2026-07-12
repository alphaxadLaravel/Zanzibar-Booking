<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookingApiController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\ContentController;
use App\Http\Controllers\Api\V1\FlightApiController;
use App\Http\Controllers\Api\V1\PaymentApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Health check — no DB (use to verify deploy)
    Route::get('ping', function () {
        return response()->json([
            'ok' => true,
            'app' => config('app.name'),
            'time' => now()->toIso8601String(),
        ]);
    });

    Route::get('flights/airports', function () {
        return response()->json([
            'data' => config('flights.airport_options', []),
            'flat' => collect(config('flights.airport_options', []))
                ->flatMap(fn ($group, $region) => collect($group)->map(fn ($label, $code) => [
                    'code' => $code,
                    'label' => $label,
                    'region' => $region,
                ]))
                ->values(),
        ]);
    });

    // Auth
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);

    // Public catalog
    Route::get('home', [CatalogController::class, 'home']);
    Route::get('deals', [CatalogController::class, 'deals']);
    Route::get('deals/{id}', [CatalogController::class, 'showDeal'])->name('api.v1.deals.show');
    Route::get('categories', [CatalogController::class, 'categories']);
    Route::get('system', [CatalogController::class, 'system']);
    Route::get('rooms/{roomId}/price', [CatalogController::class, 'roomPrice']);
    Route::get('rooms/{roomId}/calendar', [CatalogController::class, 'roomCalendar']);

    // CMS
    Route::get('pages', [ContentController::class, 'pages']);
    Route::get('pages/{slug}', [ContentController::class, 'page']);
    Route::get('blogs', [ContentController::class, 'blogs']);
    Route::get('blogs/{id}', [ContentController::class, 'showBlog']);
    Route::post('contact', [ContentController::class, 'contact']);

    // Booking lookup (public)
    Route::post('bookings/lookup', [BookingApiController::class, 'lookup']);

    // Flights search (public)
    Route::get('flights/locations', [FlightApiController::class, 'locations']);
    Route::get('flights/featured', [FlightApiController::class, 'featured']);
    Route::post('flights/search', [FlightApiController::class, 'search']);

    // Pesapal mobile callback (public, no CSRF)
    Route::match(['get', 'post'], 'payments/mobile-callback', [PaymentApiController::class, 'mobileCallback']);

    // Protected
    Route::middleware(['auth:sanctum', 'not.suspended'])->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/change-password', [AuthController::class, 'changePassword']);

        Route::get('cart', [CartController::class, 'index']);
        Route::delete('cart', [CartController::class, 'clear']);
        Route::delete('cart/{itemId}', [CartController::class, 'destroy']);
        Route::post('book/room', [CartController::class, 'bookRoom']);
        Route::post('book/deal', [CartController::class, 'bookDeal']);

        Route::post('bookings/process', [BookingApiController::class, 'process']);
        Route::get('bookings', [BookingApiController::class, 'index']);
        Route::get('bookings/{id}', [BookingApiController::class, 'show']);
        Route::post('bookings/{id}/cancel', [BookingApiController::class, 'cancel']);

        Route::post('payments/{bookingId}/pesapal', [PaymentApiController::class, 'initiatePesapal']);
        Route::get('payments/{bookingId}/offline', [PaymentApiController::class, 'offline']);
        Route::get('payments/{paymentId}/status', [PaymentApiController::class, 'status']);

        Route::post('flights/checkout', [FlightApiController::class, 'checkout']);
        Route::post('flights/book', [FlightApiController::class, 'book']);
        Route::post('flights/{bookingReference}/pay', [FlightApiController::class, 'pay']);
        Route::get('flights/my', [FlightApiController::class, 'myBookings']);
        Route::get('flights/{bookingReference}', [FlightApiController::class, 'show']);

        Route::post('deals/{id}/reviews', [ContentController::class, 'storeReview']);
    });
});
