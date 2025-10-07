<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DealsController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// MAINTENANCE MODE - Maintenance page as the index route
Route::get('/', [MaintenanceController::class, 'index'])->name('maintenance');

Route::get('/linkstorages', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
});

// Original index route moved to /home for when maintenance is disabled
Route::get('/home', [WebsiteController::class, 'index'])->name('index');
Route::get('/login', [WebsiteController::class, 'index'])->name('login');

// Authentication routes
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/change-password', [LoginController::class, 'changePassword'])->name('change-password');

// Search functionality
Route::get('/search', [WebsiteController::class, 'search'])->name('search');

// contact us
Route::get('/contact-us', [WebsiteController::class, 'contactUs'])->name('contact-us');
Route::get('/blog', [WebsiteController::class, 'blog'])->name('blog');
Route::get('/view/blog/{id}', [WebsiteController::class, 'viewBlog'])->name('view-blog');

##### DEALS
Route::get('/hotels', [WebsiteController::class, 'hotels'])->name('hotels');
Route::get('/apartments', [WebsiteController::class, 'apartments'])->name('apartments');
Route::get('/view/hotel/{id}', [WebsiteController::class, 'viewHotel'])->name('view-hotel');
Route::get('/view/apartment/{id}', [WebsiteController::class, 'viewApartment'])->name('view-apartment');
Route::get('/tours', [WebsiteController::class, 'tours'])->name('tours');
Route::get('/activities', [WebsiteController::class, 'activities'])->name('activities');
Route::get('/packages', [WebsiteController::class, 'packages'])->name('packages');
Route::get('/view/tour/{id}', [WebsiteController::class, 'viewTour'])->name('view-tour');
Route::get('/view/activity/{id}', [WebsiteController::class, 'viewActivity'])->name('view-activity');
Route::get('/view/package/{id}', [WebsiteController::class, 'viewPackage'])->name('view-package');

Route::get('/cars', [WebsiteController::class, 'cars'])->name('cars');
Route::get('/view/car/{id}', [WebsiteController::class, 'viewCar'])->name('view-car');
Route::get('/flights', [WebsiteController::class, 'flights'])->name('flights');

// Review routes
Route::post('/deals/{id}/reviews', [WebsiteController::class, 'storeReview'])->name('deals.reviews.store');

// Booking routes
Route::get('/confirm-booking', [BookingController::class, 'confirmBooking'])->name('confirm-booking');
Route::post('/process-booking', [BookingController::class, 'processBooking'])->name('process-booking');
Route::get('/offline-payment/{bookingId}', [BookingController::class, 'offlinePayment'])->name('offline.payment');
Route::get('/booking/{id}', [BookingController::class, 'viewBooking'])->name('booking.view');
Route::post('/booking/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('booking.cancel');

// Booking lookup routes
Route::get('/booking-lookup', [BookingController::class, 'bookingLookup'])->name('booking.lookup');
Route::post('/booking-lookup', [BookingController::class, 'processBookingLookup'])->name('booking.lookup');

// Deal booking routes (packages, activities, cars)
Route::post('/book-deal', [BookingController::class, 'bookDeal'])->name('book-deal');

// book rooms
Route::post('/book-room', [BookingController::class, 'bookRoom'])->name('book-room');
Route::post('/complete-booking', [BookingController::class, 'completeBooking'])->name('complete-booking');
Route::post('/book-all-cart', [BookingController::class, 'bookAllCart'])->name('book-all-cart');





// Payment routes
Route::get('/payment/{bookingId}', [PaymentController::class, 'processPayment'])->name('payment.process');

// Cart routes
Route::get('/cart', [WebsiteController::class, 'cart'])->name('cart');
Route::post('/cart/add', [WebsiteController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [BookingController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/clear', [WebsiteController::class, 'clearCart'])->name('cart.clear');

// Pesapal callback routes (excluded from CSRF protection)
Route::match(['get', 'post'], '/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success')->withoutMiddleware(['csrf']);
Route::match(['get', 'post'], '/payment/callback', [PaymentController::class, 'paymentSuccess'])->name('payment.callback')->withoutMiddleware(['csrf']); // Alternative callback URL
Route::match(['get', 'post'], '/payment/confirmation', [PaymentController::class, 'paymentConfirmation'])->name('payment.confirmation')->withoutMiddleware(['csrf']);
Route::match(['get', 'post'], '/payment/ipn', [PaymentController::class, 'paymentConfirmation'])->name('payment.ipn')->withoutMiddleware(['csrf']); // IPN callback

// Test callback routes (remove in production)
Route::get('/test/payment-callback', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Payment callback endpoint is accessible',
        'timestamp' => now(),
        'urls' => [
            'success' => route('payment.success'),
            'confirmation' => route('payment.confirmation'),
            'callback' => route('payment.callback'),
            'ipn' => route('payment.ipn')
        ]
    ]);
})->name('test.payment.callback');


##########################################################################################
##########################################################################################

// wrap auth
Route::middleware('auth')->group(function () {

    ### ADMIN
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // deals
    Route::get('/admin/deals/{dealType}', [DealsController::class, 'dealType'])->name('admin.deal');
    Route::get('/admin/manage-deal/{id}/{type}/edit', [DealsController::class, 'editDeal'])->name('admin.manage-deal.edit');
    Route::put('/admin/manage-deal/{id}/{type}/update', [DealsController::class, 'updateDeal'])->name('admin.manage-deal.update');
    Route::get('/admin/manage-deal/{type}', [DealsController::class, 'manageDeal'])->name('admin.manage-deal');
    Route::post('/admin/manage-deal/{type}/store', [DealsController::class, 'storeDeal'])->name('admin.manage-deal.store');

    // amanage tours
    Route::get('/admin/tours/{id}/manage', [DealsController::class, 'manageTour'])->name('admin.tours.manage');
    Route::delete('/admin/tours/{id}', [DealsController::class, 'deleteTour'])->name('admin.tours.delete');
    Route::post('/admin/tours/{tourId}/itinerary', [DealsController::class, 'storeItinerary'])->name('admin.tours.itinerary.store');
    Route::get('/admin/tours/{tourId}/itinerary/{itineraryId}', [DealsController::class, 'getItinerary'])->name('admin.tours.itinerary.get');
    Route::put('/admin/tours/{tourId}/itinerary/{itineraryId}', [DealsController::class, 'updateItinerary'])->name('admin.tours.itinerary.update');
    Route::delete('/admin/tours/{tourId}/itinerary/{itineraryId}', [DealsController::class, 'deleteItinerary'])->name('admin.tours.itinerary.delete');


    Route::get('/admin/hotels/{id}/manage', [DealsController::class, 'manageHotel'])->name('admin.hotels.manage');
    Route::delete('/admin/hotels/{id}', [DealsController::class, 'deleteHotel'])->name('admin.hotels.delete');

    Route::delete('/admin/cars/{id}', [DealsController::class, 'deleteCar'])->name('admin.cars.delete');

    Route::delete('/admin/apartments/{id}', [DealsController::class, 'deleteApartment'])->name('admin.apartments.delete');



    #######################################################################################################################
    #######################################################################################################################



    // Blog Management
    Route::get('/admin/blog', [AdminController::class, 'blog'])->name('admin.blog');
    Route::get('/admin/blog/create', [AdminController::class, 'createBlog'])->name('admin.blog.create');
    Route::post('/admin/blog/store', [AdminController::class, 'storeBlog'])->name('admin.blog.store');
    Route::get('/admin/blog/{id}/edit', [AdminController::class, 'editBlog'])->name('admin.blog.edit');
    Route::put('/admin/blog/{id}', [AdminController::class, 'updateBlog'])->name('admin.blog.update');
    Route::delete('/admin/blog/{id}', [AdminController::class, 'deleteBlog'])->name('admin.blog.delete');

    // Bookings Management
    Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::get('/admin/bookings/{id}', [AdminController::class, 'viewBooking'])->name('admin.bookings.view');
    Route::put('/admin/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.bookings.status');

    // Users Management
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::put('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');
    Route::get('/admin/users/roles', [AdminController::class, 'userRoles'])->name('admin.users.roles');

    // Payments Management
    Route::get('/admin/payments', [AdminController::class, 'payments'])->name('admin.payments');
    Route::get('/admin/payments/{id}', [AdminController::class, 'viewPayment'])->name('admin.payments.view');

    // Media Management
    Route::get('/admin/media', [AdminController::class, 'media'])->name('admin.media');
    Route::post('/admin/media/upload', [AdminController::class, 'uploadMedia'])->name('admin.media.upload');
    Route::delete('/admin/media/{id}', [AdminController::class, 'deleteMedia'])->name('admin.media.delete');

    // Settings Management
    Route::get('/admin/settings/general', [AdminController::class, 'generalSettings'])->name('admin.settings.general');
    Route::put('/admin/settings/general', [AdminController::class, 'updateGeneralSettings'])->name('admin.settings.general.update');
    Route::get('/admin/settings/security', [AdminController::class, 'securitySettings'])->name('admin.settings.security');
    Route::put('/admin/settings/security', [AdminController::class, 'updateSecuritySettings'])->name('admin.settings.security.update');

    // Profile Management
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
    Route::put('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // My Bookings
    Route::get('/admin/my-bookings', [AdminController::class, 'myBookings'])->name('admin.my-bookings');


    #############################################################

    // Categories Management
    Route::get('/categories', [CategoriesController::class, 'categories'])->name('admin.categories');
    Route::post('/admin/categories/store', [CategoriesController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/categories/{id}/edit', [CategoriesController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/admin/categories/{id}', [CategoriesController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{id}', [CategoriesController::class, 'delete'])->name('admin.categories.delete');

    // Features Management
    Route::get('/admin/features', [FeatureController::class, 'index'])->name('admin.features');
    Route::post('/admin/features/store', [FeatureController::class, 'store'])->name('admin.features.store');
    Route::put('/admin/features/{id}', [FeatureController::class, 'update'])->name('admin.features.update');
    Route::delete('/admin/features/{id}', [FeatureController::class, 'destroy'])->name('admin.features.delete');
    Route::put('/admin/features/{id}/toggle-status', [FeatureController::class, 'toggleStatus'])->name('admin.features.toggle-status');

    // manage deal (create and edit) - specific routes first


    // hotels management

    // room management
    Route::post('/admin/hotels/{hotel_id}/rooms', [DealsController::class, 'storeRoom'])->name('admin.rooms.store');
    Route::get('/admin/hotels/{hotel_id}/rooms/{room_id}/edit', [DealsController::class, 'editRoom'])->name('admin.rooms.edit');
    Route::put('/admin/hotels/{hotel_id}/rooms/{room_id}', [DealsController::class, 'updateRoom'])->name('admin.rooms.update');
    Route::delete('/admin/hotels/{hotel_id}/rooms/{room_id}', [DealsController::class, 'deleteRoom'])->name('admin.rooms.delete');


    // Tours Management
    Route::get('/admin/activities', [DealsController::class, 'activities'])->name('admin.activities');
    Route::get('/admin/activities/create', [DealsController::class, 'createActivity'])->name('admin.activities.create');
    Route::post('/admin/activities/store', [DealsController::class, 'storeActivity'])->name('admin.activities.store');
    Route::get('/admin/activities/{id}/edit', [DealsController::class, 'editActivity'])->name('admin.activities.edit');
    Route::put('/admin/activities/{id}', [DealsController::class, 'updateActivity'])->name('admin.activities.update');
    Route::delete('/admin/activities/{id}', [DealsController::class, 'deleteActivity'])->name('admin.activities.delete');

    // Tour Management
    Route::get('/admin/activities/{id}/manage', [DealsController::class, 'manageActivity'])->name('admin.activities.manage');

    // packages
    Route::get('/admin/packages', [DealsController::class, 'packages'])->name('admin.packages');
    Route::get('/admin/packages/create', [DealsController::class, 'createPackage'])->name('admin.packages.create');
    Route::post('/admin/packages/store', [DealsController::class, 'storePackage'])->name('admin.packages.store');
    Route::get('/admin/packages/{id}/edit', [DealsController::class, 'editPackage'])->name('admin.packages.edit');
    Route::put('/admin/packages/{id}', [DealsController::class, 'updatePackage'])->name('admin.packages.update');
    Route::delete('/admin/packages/{id}', [DealsController::class, 'deletePackage'])->name('admin.packages.delete');

    // Package Management
    Route::get('/admin/packages/{id}/manage', [DealsController::class, 'managePackage'])->name('admin.packages.manage');

    // Tour Itinerary CRUD

    // Nearby Deals Management
    Route::get('/admin/hotels/{hotelId}/get-deals-by-type/{type}', [DealsController::class, 'getDealsByType'])->name('admin.hotels.get-deals-by-type');
    Route::post('/admin/hotels/{hotelId}/add-nearby', [DealsController::class, 'addNearbyDeals'])->name('admin.hotels.add-nearby');
    Route::delete('/admin/hotels/{hotelId}/remove-nearby/{nearId}', [DealsController::class, 'removeNearbyDeal'])->name('admin.hotels.remove-nearby');
});

// MAINTENANCE MODE INSTRUCTIONS:
// To disable maintenance mode:
// 1. Comment out or delete line 16: Route::get('/', [MaintenanceController::class, 'index'])->name('maintenance');
// 2. Change line 26 from: Route::get('/home', [WebsiteController::class, 'index'])->name('index');
//    To: Route::get('/', [WebsiteController::class, 'index'])->name('index');
// 3. Clear your route cache: php artisan route:clear
