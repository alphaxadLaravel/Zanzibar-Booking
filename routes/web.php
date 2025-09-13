<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebsiteController::class, 'index'])->name('index');

// contact us
Route::get('/contact-us', [WebsiteController::class, 'contactUs'])->name('contact-us');
Route::get('/blog', [WebsiteController::class, 'blog'])->name('blog');


##### DEALS
Route::get('/hotels', [WebsiteController::class, 'hotels'])->name('hotels');
Route::get('/view/hotel', [WebsiteController::class, 'viewHotel'])->name('view-hotel');
Route::get('/tours', [WebsiteController::class, 'tours'])->name('tours');
Route::get('/view/tour', [WebsiteController::class, 'viewTour'])->name('view-tour');

Route::get('/cars', [WebsiteController::class, 'cars'])->name('cars');
Route::get('/flights', [WebsiteController::class, 'flights'])->name('flights');


