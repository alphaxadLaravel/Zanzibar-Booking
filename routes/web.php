<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DealsController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebsiteController::class, 'index'])->name('index');

// contact us
Route::get('/contact-us', [WebsiteController::class, 'contactUs'])->name('contact-us');
Route::get('/blog', [WebsiteController::class, 'blog'])->name('blog');
Route::get('/view/blog', [WebsiteController::class, 'viewBlog'])->name('view-blog');

##### DEALS
Route::get('/hotels', [WebsiteController::class, 'hotels'])->name('hotels');
Route::get('/apartments', [WebsiteController::class, 'apartments'])->name('apartments');
Route::get('/view/hotel/{id}', [WebsiteController::class, 'viewHotel'])->name('view-hotel');
Route::get('/view/apartment/{id}', [WebsiteController::class, 'viewApartment'])->name('view-apartment');
Route::get('/tours', [WebsiteController::class, 'tours'])->name('tours');
Route::get('/view/tour/{id}', [WebsiteController::class, 'viewTour'])->name('view-tour');

Route::get('/cars', [WebsiteController::class, 'cars'])->name('cars');
Route::get('/view/car/{id}', [WebsiteController::class, 'viewCar'])->name('view-car');
Route::get('/flights', [WebsiteController::class, 'flights'])->name('flights');

// Booking routes
Route::get('/confirm-booking', [WebsiteController::class, 'confirmBooking'])->name('confirm-booking');
Route::post('/process-booking', [WebsiteController::class, 'processBooking'])->name('process.booking');


##########################################################################################
### ADMIN
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

// Hotel Rooms Management
Route::get('/admin/hotels/{hotel_id}/rooms', [AdminController::class, 'hotelRooms'])->name('admin.hotels.rooms');
Route::get('/admin/hotels/{hotel_id}/rooms/create', [AdminController::class, 'createHotelRoom'])->name('admin.hotels.rooms.create');
Route::post('/admin/hotels/{hotel_id}/rooms/store', [AdminController::class, 'storeHotelRoom'])->name('admin.hotels.rooms.store');
Route::get('/admin/hotels/{hotel_id}/rooms/{room_id}/edit', [AdminController::class, 'editHotelRoom'])->name('admin.hotels.rooms.edit');
Route::put('/admin/hotels/{hotel_id}/rooms/{room_id}', [AdminController::class, 'updateHotelRoom'])->name('admin.hotels.rooms.update');
Route::delete('/admin/hotels/{hotel_id}/rooms/{room_id}', [AdminController::class, 'deleteHotelRoom'])->name('admin.hotels.rooms.delete');
Route::get('/admin/hotels/{hotel_id}/rooms/{room_id}/view', [AdminController::class, 'viewHotelRoom'])->name('admin.hotels.rooms.view');
Route::put('/admin/hotels/{hotel_id}/rooms/{room_id}/availability', [AdminController::class, 'updateRoomAvailability'])->name('admin.hotels.rooms.availability');

// Apartments Management
Route::get('/admin/apartments', [DealsController::class, 'apartments'])->name('admin.apartments');
Route::get('/admin/apartments/create', [DealsController::class, 'createApartment'])->name('admin.apartments.create');
Route::post('/admin/apartments/store', [DealsController::class, 'storeApartment'])->name('admin.apartments.store');
Route::get('/admin/apartments/{id}/edit', [DealsController::class, 'editApartment'])->name('admin.apartments.edit');
Route::put('/admin/apartments/{id}', [DealsController::class, 'updateApartment'])->name('admin.apartments.update');
Route::delete('/admin/apartments/{id}', [DealsController::class, 'deleteApartment'])->name('admin.apartments.delete');



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
Route::get('/admin/manage-deal/{id}/{type}/edit', [DealsController::class, 'editDeal'])->name('admin.manage-deal.edit');
Route::put('/admin/manage-deal/{id}/{type}/update', [DealsController::class, 'updateDeal'])->name('admin.manage-deal.update');
Route::get('/admin/manage-deal/{type}', [DealsController::class, 'manageDeal'])->name('admin.manage-deal');
Route::post('/admin/manage-deal/{type}/store', [DealsController::class, 'storeDeal'])->name('admin.manage-deal.store');

// hotels management
Route::get('/admin/hotels', [DealsController::class, 'hotels'])->name('admin.hotels');
Route::get('/admin/hotels/{id}/manage', [DealsController::class, 'manageHotel'])->name('admin.hotels.manage');
Route::delete('/admin/hotels/{id}', [DealsController::class, 'deleteHotel'])->name('admin.hotels.delete');

// room management
Route::post('/admin/hotels/{hotel_id}/rooms', [DealsController::class, 'storeRoom'])->name('admin.rooms.store');
Route::get('/admin/hotels/{hotel_id}/rooms/{room_id}/edit', [DealsController::class, 'editRoom'])->name('admin.rooms.edit');
Route::put('/admin/hotels/{hotel_id}/rooms/{room_id}', [DealsController::class, 'updateRoom'])->name('admin.rooms.update');
Route::delete('/admin/hotels/{hotel_id}/rooms/{room_id}', [DealsController::class, 'deleteRoom'])->name('admin.rooms.delete');

// Cars Management
Route::get('/admin/cars', [DealsController::class, 'cars'])->name('admin.cars');
Route::get('/admin/cars/create', [DealsController::class, 'createCar'])->name('admin.cars.create');
Route::post('/admin/cars/store', [DealsController::class, 'storeCar'])->name('admin.cars.store');
Route::get('/admin/cars/{id}/edit', [DealsController::class, 'editCar'])->name('admin.cars.edit');
Route::put('/admin/cars/{id}', [DealsController::class, 'updateCar'])->name('admin.cars.update');
Route::delete('/admin/cars/{id}', [DealsController::class, 'deleteCar'])->name('admin.cars.delete');

// Tours Management
Route::get('/admin/tours', [DealsController::class, 'tours'])->name('admin.tours');
Route::get('/admin/tours/create', [DealsController::class, 'createTour'])->name('admin.tours.create');
Route::post('/admin/tours/store', [DealsController::class, 'storeTour'])->name('admin.tours.store');
Route::get('/admin/tours/{id}/edit', [DealsController::class, 'editTour'])->name('admin.tours.edit');
Route::put('/admin/tours/{id}', [DealsController::class, 'updateTour'])->name('admin.tours.update');
Route::delete('/admin/tours/{id}', [DealsController::class, 'deleteTour'])->name('admin.tours.delete');

// Tour Management
Route::get('/admin/tours/{id}/manage', [DealsController::class, 'manageTour'])->name('admin.tours.manage');

// Tour Itinerary CRUD
Route::post('/admin/tours/{tourId}/itinerary', [DealsController::class, 'storeItinerary'])->name('admin.tours.itinerary.store');
Route::get('/admin/tours/{tourId}/itinerary/{itineraryId}', [DealsController::class, 'getItinerary'])->name('admin.tours.itinerary.get');
Route::put('/admin/tours/{tourId}/itinerary/{itineraryId}', [DealsController::class, 'updateItinerary'])->name('admin.tours.itinerary.update');
Route::delete('/admin/tours/{tourId}/itinerary/{itineraryId}', [DealsController::class, 'deleteItinerary'])->name('admin.tours.itinerary.delete');

// Nearby Deals Management
Route::get('/admin/hotels/{hotelId}/get-deals-by-type/{type}', [DealsController::class, 'getDealsByType'])->name('admin.hotels.get-deals-by-type');
Route::post('/admin/hotels/{hotelId}/add-nearby', [DealsController::class, 'addNearbyDeals'])->name('admin.hotels.add-nearby');
Route::delete('/admin/hotels/{hotelId}/remove-nearby/{nearId}', [DealsController::class, 'removeNearbyDeal'])->name('admin.hotels.remove-nearby');