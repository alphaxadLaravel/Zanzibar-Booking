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

    // flights
    public function flights()
    {
        return view('website.pages.flights');
    }
}
