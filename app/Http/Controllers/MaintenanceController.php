<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Display the maintenance mode page
     */
    public function index()
    {
        return view('maintenance.index');
    }
}
