<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DealsController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// MAINTENANCE MODE - Maintenance page as the index route
// Route::get('/', [MaintenanceController::class, 'index'])->name('maintenance');

Route::get('/linkstorages', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
});

// Original index route moved to /home for when maintenance is disabled
Route::get('/', [WebsiteController::class, 'index'])->name('index');
Route::get('/login', [WebsiteController::class, 'index'])->name('login');

// Authentication routes
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/change-password', [LoginController::class, 'changePassword'])->name('change-password');

// Email Verification Routes
Route::get('/email/verify/{id}/{hash}', [LoginController::class, 'verifyEmail'])->name('verification.verify');
Route::get('/email/verification-notice', function () {
    return view('website.pages.verify-email');
})->name('verification.notice');

// Newsletter Routes
Route::post('/newsletter/subscribe', [WebsiteController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe', [WebsiteController::class, 'unsubscribeNewsletter'])->name('newsletter.unsubscribe');

// Search functionality
Route::get('/search/category/{category}', [WebsiteController::class, 'searchByCategory'])->name('search.category');
Route::get('/search', [WebsiteController::class, 'search'])->name('search');

// Partner routes
Route::middleware('auth')->group(function () {
    Route::post('/partner/request', [PartnerController::class, 'request'])->name('partner.request');
});

// contact us
Route::get('/contact-us', [WebsiteController::class, 'contactUs'])->name('contact-us');
Route::post('/contact-us', [WebsiteController::class, 'submitContactForm'])->name('contact.submit');

// Dynamic pages (About Us, Terms, Privacy, etc.)
Route::get('/page/{slug}', [WebsiteController::class, 'showPage'])->name('page.show');

Route::get('/blog', [WebsiteController::class, 'blog'])->name('blog');
Route::get('/view/blog/{id}', [WebsiteController::class, 'viewBlog'])->name('view-blog');

##### DEALS
Route::get('/hotels', [WebsiteController::class, 'hotels'])->name('hotels');
Route::get('/apartments', [WebsiteController::class, 'apartments'])->name('apartments');
Route::get('/view/hotel/{id}', [WebsiteController::class, 'viewHotel'])->name('view-hotel');
Route::get('/view/apartment/{id}', [WebsiteController::class, 'viewApartment'])->name('view-apartment');
Route::get('/tours', [WebsiteController::class, 'tours'])->name('tours');
Route::get('/activities', [WebsiteController::class, 'activities'])->name('activities');
Route::get('/explore-packages', [WebsiteController::class, 'packages'])->name('packages');
Route::get('/view/tour/{id}', [WebsiteController::class, 'viewTour'])->name('view-tour');
Route::get('/view/activity/{id}', [WebsiteController::class, 'viewActivity'])->name('view-activity');
Route::get('/view/package/{id}', [WebsiteController::class, 'viewPackage'])->name('view-package');

Route::get('/cars', [WebsiteController::class, 'cars'])->name('cars');
Route::get('/view/car/{id}', [WebsiteController::class, 'viewCar'])->name('view-car');

// Flight routes
Route::get('/flights', [App\Http\Controllers\FlightController::class, 'index'])->name('flights.index');
Route::get('/flights/search-locations', [App\Http\Controllers\FlightController::class, 'searchLocations'])->name('flights.search-locations');
Route::get('/flights/{flightId}', [App\Http\Controllers\FlightController::class, 'show'])->name('flights.show');
Route::get('/flights/{flightId}/book', [App\Http\Controllers\FlightController::class, 'bookingForm'])->name('flights.booking.form');
Route::post('/flights/book', [App\Http\Controllers\FlightController::class, 'processBooking'])->name('flights.booking.process');
Route::get('/flights/payment/{bookingReference}', [App\Http\Controllers\FlightController::class, 'payment'])->name('flights.payment');
Route::get('/flights/payment/{bookingReference}/initialize', [App\Http\Controllers\FlightController::class, 'initializePayment'])->name('flights.payment.initialize');
Route::get('/flights/payment/callback', [App\Http\Controllers\FlightController::class, 'paymentCallback'])->name('flights.payment.callback');
Route::get('/flights/confirmation/{bookingReference}', [App\Http\Controllers\FlightController::class, 'confirmation'])->name('flights.confirmation');

// User flight bookings (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('/my-flights', [App\Http\Controllers\FlightController::class, 'myBookings'])->name('flights.my-bookings');
});

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
Route::post('/booking-lookup', [BookingController::class, 'processBookingLookup'])->name('booking.lookup.post');

// Deal booking routes (packages, activities, cars)
Route::post('/book-deal', [BookingController::class, 'bookDeal'])->name('book-deal');

// book rooms
Route::get('/room/{roomId}/price', [WebsiteController::class, 'getRoomPrice'])->name('room.price');
Route::get('/room/{roomId}/prices-calendar', [WebsiteController::class, 'getRoomPricesCalendar'])->name('room.prices-calendar');
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



##########################################################################################
##########################################################################################

// wrap auth + admin panel access
Route::middleware(['auth', 'admin.panel'])->group(function () {

    ### ADMIN
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Super Admin — admin account management
    Route::middleware('super.admin')->prefix('admin/admins')->name('admin.admins.')->group(function () {
        Route::get('/', [AdminController::class, 'admins'])->name('index');
        Route::get('/create', [AdminController::class, 'createAdmin'])->name('create');
        Route::post('/', [AdminController::class, 'storeAdmin'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editAdmin'])->name('edit');
        Route::put('/{id}/password', [AdminController::class, 'updateAdminPassword'])->name('password.update');
        Route::put('/{id}', [AdminController::class, 'updateAdmin'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'deactivateAdmin'])->name('delete');
    });

    // deals — view (dynamic type from route)
    Route::middleware('permission.deal:view,partner')->group(function () {
        Route::get('/admin/deals/{dealType}', [DealsController::class, 'dealType'])->name('admin.deal');
        Route::get('/admin/manage-deal/{id}/{type}/edit', [DealsController::class, 'editDeal'])->name('admin.manage-deal.edit');
        Route::get('/admin/manage-deal/{type}', [DealsController::class, 'manageDeal'])->name('admin.manage-deal');
        Route::get('/admin/tours/{id}/manage', [DealsController::class, 'manageTour'])->name('admin.tours.manage');
    });

    Route::middleware('permission.deal:view,partner,hotel')->group(function () {
        Route::get('/admin/hotels/{id}/manage', [DealsController::class, 'manageHotel'])->name('admin.hotels.manage');
        Route::get('/admin/hotels/{hotelId}/get-deals-by-type/{type}', [DealsController::class, 'getDealsByType'])->name('admin.hotels.get-deals-by-type');
    });

    Route::middleware('permission.deal:view,partner,activity')->group(function () {
        Route::get('/admin/activities', [DealsController::class, 'activities'])->name('admin.activities');
        Route::get('/admin/activities/{id}/manage', [DealsController::class, 'manageActivity'])->name('admin.activities.manage');
    });

    Route::middleware('permission.deal:view,partner,package')->group(function () {
        Route::get('/admin/packages', [DealsController::class, 'packages'])->name('admin.packages');
        Route::get('/admin/packages/{id}/manage', [DealsController::class, 'managePackage'])->name('admin.packages.manage');
    });

    // deals — create
    Route::middleware('permission.deal:create,partner')->group(function () {
        Route::post('/admin/manage-deal/{type}/store', [DealsController::class, 'storeDeal'])->name('admin.manage-deal.store');
    });

    Route::middleware('permission.deal:create,partner,activity')->group(function () {
        Route::get('/admin/activities/create', [DealsController::class, 'createActivity'])->name('admin.activities.create');
        Route::post('/admin/activities/store', [DealsController::class, 'storeActivity'])->name('admin.activities.store');
        Route::post('/admin/tours/{tourId}/itinerary', [DealsController::class, 'storeItinerary'])->name('admin.tours.itinerary.store');
    });

    Route::middleware('permission.deal:create,partner,package')->group(function () {
        Route::get('/admin/packages/create', [DealsController::class, 'createPackage'])->name('admin.packages.create');
        Route::post('/admin/packages/store', [DealsController::class, 'storePackage'])->name('admin.packages.store');
    });

    Route::middleware('permission.deal:create,partner,hotel')->group(function () {
        Route::post('/admin/hotels/{hotel_id}/rooms', [DealsController::class, 'storeRoom'])->name('admin.rooms.store');
        Route::post('/admin/hotels/{hotelId}/add-nearby', [DealsController::class, 'addNearbyDeals'])->name('admin.hotels.add-nearby');
    });

    // deals — edit
    Route::middleware('permission.deal:edit,partner')->group(function () {
        Route::put('/admin/manage-deal/{id}/{type}/update', [DealsController::class, 'updateDeal'])->name('admin.manage-deal.update');
    });

    Route::middleware('permission.deal:edit,partner,activity')->group(function () {
        Route::get('/admin/activities/{id}/edit', [DealsController::class, 'editActivity'])->name('admin.activities.edit');
        Route::put('/admin/activities/{id}', [DealsController::class, 'updateActivity'])->name('admin.activities.update');
        Route::get('/admin/tours/{tourId}/itinerary/{itineraryId}', [DealsController::class, 'getItinerary'])->name('admin.tours.itinerary.get');
        Route::put('/admin/tours/{tourId}/itinerary/{itineraryId}', [DealsController::class, 'updateItinerary'])->name('admin.tours.itinerary.update');
    });

    Route::middleware('permission.deal:edit,partner,package')->group(function () {
        Route::get('/admin/packages/{id}/edit', [DealsController::class, 'editPackage'])->name('admin.packages.edit');
        Route::put('/admin/packages/{id}', [DealsController::class, 'updatePackage'])->name('admin.packages.update');
    });

    Route::middleware('permission.deal:edit,partner,hotel')->group(function () {
        Route::get('/admin/hotels/{hotel_id}/rooms/{room_id}/edit', [DealsController::class, 'editRoom'])->name('admin.rooms.edit');
        Route::put('/admin/hotels/{hotel_id}/rooms/{room_id}', [DealsController::class, 'updateRoom'])->name('admin.rooms.update');
    });

    // deals — delete
    Route::middleware('permission.deal:delete,partner,hotel')->group(function () {
        Route::delete('/admin/hotels/{id}', [DealsController::class, 'deleteHotel'])->name('admin.hotels.delete');
        Route::delete('/admin/hotels/{hotel_id}/rooms/{room_id}', [DealsController::class, 'deleteRoom'])->name('admin.rooms.delete');
        Route::delete('/admin/hotels/{hotelId}/remove-nearby/{nearId}', [DealsController::class, 'removeNearbyDeal'])->name('admin.hotels.remove-nearby');
    });

    Route::middleware('permission.deal:delete,partner,car')->group(function () {
        Route::delete('/admin/cars/{id}', [DealsController::class, 'deleteCar'])->name('admin.cars.delete');
    });

    Route::middleware('permission.deal:delete,partner,apartment')->group(function () {
        Route::delete('/admin/apartments/{id}', [DealsController::class, 'deleteApartment'])->name('admin.apartments.delete');
    });

    Route::middleware('permission.deal:delete,partner,activity')->group(function () {
        Route::delete('/admin/tours/{id}', [DealsController::class, 'deleteTour'])->name('admin.tours.delete');
        Route::delete('/admin/activities/{id}', [DealsController::class, 'deleteActivity'])->name('admin.activities.delete');
        Route::delete('/admin/tours/{tourId}/itinerary/{itineraryId}', [DealsController::class, 'deleteItinerary'])->name('admin.tours.itinerary.delete');
    });

    Route::middleware('permission.deal:delete,partner,package')->group(function () {
        Route::delete('/admin/packages/{id}', [DealsController::class, 'deletePackage'])->name('admin.packages.delete');
    });

    Route::get('/admin/partners/{user}/approve', [AdminController::class, 'approvePartner'])
        ->middleware(['signed', 'permission:partners.manage'])
        ->name('admin.partners.approve');
    Route::get('/admin/partners/{user}/reject', [AdminController::class, 'rejectPartner'])
        ->middleware(['signed', 'permission:partners.manage'])
        ->name('admin.partners.reject');



    #######################################################################################################################
    #######################################################################################################################



    // Blog Management
    Route::middleware('permission:blog.view')->group(function () {
        Route::get('/admin/blog', [AdminController::class, 'blog'])->name('admin.blog');
    });
    Route::middleware('permission:blog.create')->group(function () {
        Route::get('/admin/blog/create', [AdminController::class, 'createBlog'])->name('admin.blog.create');
        Route::post('/admin/blog/store', [AdminController::class, 'storeBlog'])->name('admin.blog.store');
    });
    Route::middleware('permission:blog.edit')->group(function () {
        Route::get('/admin/blog/{id}/edit', [AdminController::class, 'editBlog'])->name('admin.blog.edit');
        Route::put('/admin/blog/{id}', [AdminController::class, 'updateBlog'])->name('admin.blog.update');
    });
    Route::delete('/admin/blog/{id}', [AdminController::class, 'deleteBlog'])
        ->middleware('permission:blog.delete')
        ->name('admin.blog.delete');

    // Bookings Management
    Route::middleware('permission:bookings.view')->group(function () {
        Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
        Route::get('/admin/bookings/{id}', [AdminController::class, 'viewBooking'])->name('admin.bookings.view');
        Route::get('/admin/my-bookings', [AdminController::class, 'myBookings'])->name('admin.my-bookings');
    });
    Route::put('/admin/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])
        ->middleware('permission:bookings.manage')
        ->name('admin.bookings.status');

    // Users Management
    Route::middleware('permission:users.view')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
    });
    Route::middleware('permission:users.manage')->group(function () {
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::put('/admin/users/{id}/suspend', [AdminController::class, 'suspendUser'])->name('admin.users.suspend');
        Route::put('/admin/users/{id}/unsuspend', [AdminController::class, 'unsuspendUser'])->name('admin.users.unsuspend');
        Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    });

    // Partners Management
    Route::middleware('permission:partners.view')->group(function () {
        Route::get('/admin/partners', [AdminController::class, 'partners'])->name('admin.partners');
    });
    Route::middleware('permission:partners.manage')->group(function () {
        Route::get('/admin/partners/{id}/assign-deals', [AdminController::class, 'assignDealsToPartnerForm'])->name('admin.partners.assign-deals');
        Route::post('/admin/partners/{id}/assign-deals', [AdminController::class, 'assignDealsToPartner'])->name('admin.partners.assign-deals.store');
        Route::put('/admin/users/{id}/partner/approve', [AdminController::class, 'approvePartnerRequest'])->name('admin.users.partner.approve');
        Route::put('/admin/users/{id}/partner/reject', [AdminController::class, 'rejectPartnerRequest'])->name('admin.users.partner.reject');
    });

    // Payments Management
    Route::middleware('permission:payments.view')->group(function () {
        Route::get('/admin/payments', [AdminController::class, 'payments'])->name('admin.payments');
        Route::get('/admin/payments/{id}', [AdminController::class, 'viewPayment'])->name('admin.payments.view');
    });

    // Media & general settings
    Route::middleware('permission:settings.system')->group(function () {
        Route::get('/admin/media', [AdminController::class, 'media'])->name('admin.media');
        Route::post('/admin/media/upload', [AdminController::class, 'uploadMedia'])->name('admin.media.upload');
        Route::delete('/admin/media/{id}', [AdminController::class, 'deleteMedia'])->name('admin.media.delete');
        Route::get('/admin/settings/general', [AdminController::class, 'generalSettings'])->name('admin.settings.general');
        Route::put('/admin/settings/general', [AdminController::class, 'updateGeneralSettings'])->name('admin.settings.general.update');
        Route::get('/admin/settings/security', [AdminController::class, 'securitySettings'])->name('admin.settings.security');
        Route::put('/admin/settings/security', [AdminController::class, 'updateSecuritySettings'])->name('admin.settings.security.update');
        Route::get('/admin/system-settings', [AdminController::class, 'systemSettings'])->name('admin.system.settings');
        Route::put('/admin/system-settings', [AdminController::class, 'updateSystemSettings'])->name('admin.system.settings.update');
    });

    // CMS pages
    Route::middleware('permission:settings.content')->group(function () {
        Route::get('/admin/manage/content/{page}', [AdminController::class, 'manageContent'])->name('admin.manage.content');
        Route::put('/admin/manage/content/{page}', [AdminController::class, 'updateContent'])->name('admin.manage.content.update');
    });

    // Home SEO
    Route::middleware('permission:settings.seo')->group(function () {
        Route::get('/admin/home-seo', [AdminController::class, 'homeSeoSettings'])->name('admin.home.seo');
        Route::put('/admin/home-seo', [AdminController::class, 'updateHomeSeoSettings'])->name('admin.home.seo.update');
    });

    // Profile Management (all admins and partners)
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
    Route::put('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // Categories Management
    Route::middleware('permission:categories.manage')->group(function () {
        Route::get('/categories', [CategoriesController::class, 'categories'])->name('admin.categories');
        Route::post('/admin/categories/store', [CategoriesController::class, 'store'])->name('admin.categories.store');
        Route::get('/admin/categories/{id}/edit', [CategoriesController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/admin/categories/{id}', [CategoriesController::class, 'update'])->name('admin.categories.update');
        Route::delete('/admin/categories/{id}', [CategoriesController::class, 'delete'])->name('admin.categories.delete');
    });

    // Features Management
    Route::middleware('permission:features.manage')->group(function () {
        Route::get('/admin/features', [FeatureController::class, 'index'])->name('admin.features');
        Route::post('/admin/features/store', [FeatureController::class, 'store'])->name('admin.features.store');
        Route::put('/admin/features/{id}', [FeatureController::class, 'update'])->name('admin.features.update');
        Route::delete('/admin/features/{id}', [FeatureController::class, 'destroy'])->name('admin.features.delete');
        Route::put('/admin/features/{id}/toggle-status', [FeatureController::class, 'toggleStatus'])->name('admin.features.toggle-status');
    });

    // Contact messages management
    Route::middleware('permission:contact.view')->group(function () {
        Route::get('/admin/contact-messages', [AdminController::class, 'contactMessages'])->name('admin.contact.messages');
        Route::get('/admin/contact-messages/{id}', [AdminController::class, 'viewContactMessage'])->name('admin.contact.message.view');
    });
    Route::middleware('permission:contact.manage')->group(function () {
        Route::post('/admin/contact-messages/{id}/status', [AdminController::class, 'updateMessageStatus'])->name('admin.contact.message.status');
        Route::delete('/admin/contact-messages/{id}', [AdminController::class, 'deleteContactMessage'])->name('admin.contact.message.delete');
    });
});
