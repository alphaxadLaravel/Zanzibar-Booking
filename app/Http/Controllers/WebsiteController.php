<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    //index
    public function index()
    {
        return view('website.pages.index');
    }

    // contactUs
    public function contactUs()
    {
        return view('website.pages.contact_us');
    }

    // blog
    public function blog()
    {
        return view('website.pages.blog');
    }

    // viewBlog
    public function viewBlog()
    {
        return view('website.pages.view_post');
    }

    // hotels
    public function hotels()
    {
        return view('website.pages.hotels');
    }

    // tours
    public function tours()
    {
        return view('website.pages.tours');
    }

    // viewTour
    public function viewTour()
    {
        return view('website.pages.view_tour');
    }

    // viewHotel
    public function viewHotel()
    {
        return view('website.pages.view_hotel');
    }

    // cars
    public function cars()
    {
        return view('website.pages.cars');
    }

    // viewCar
    public function viewCar()
    {
        return view('website.pages.view_car');
    }
    


    // flights
    public function flights()
    {
        return view('website.pages.flights');
    }

    // confirmBooking
    public function confirmBooking()
    {
        return view('website.pages.confirm_booking');
    }

    // processBooking
    public function processBooking(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'nationality' => 'required|string|max:100',
            'passport_number' => 'required|string|max:50',
            'payment_method' => 'required|in:pesapal,offline',
            'agree_terms' => 'required|accepted',
        ]);

        // Process the booking based on payment method
        if ($request->payment_method === 'pesapal') {
            // Redirect to Pesapal payment gateway
            return redirect()->route('pesapal.payment')->with('booking_data', $request->all());
        } else {
            // Process offline payment
            // Save booking to database
            // Send confirmation email
            return redirect()->route('booking.success')->with('success', 'Your booking has been confirmed! You will pay on arrival.');
        }
    }
}
