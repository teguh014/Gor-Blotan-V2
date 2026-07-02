<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Show the public landing page with all active courts.
     */
    public function index(): View
    {
        $courts = Court::getActiveCached(['id', 'name', 'description', 'price_per_hour', 'facilities', 'is_active']);

        return view('welcome', compact('courts'));
    }
}
