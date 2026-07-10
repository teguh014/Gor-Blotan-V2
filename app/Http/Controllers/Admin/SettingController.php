<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show the venue settings page.
     */
    public function index(): View
    {
        return view('admin.settings.index', [
            'venueName'    => Setting::get('venue_name', config('app.name')),
            'venueTagline' => Setting::get('venue_tagline', 'Booking Lapangan Online'),
        ]);
    }

    /**
     * Save venue settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'venue_name'    => ['required', 'string', 'max:80'],
            'venue_tagline' => ['nullable', 'string', 'max:120'],
        ]);

        Setting::set('venue_name', $request->venue_name);
        Setting::set('venue_tagline', $request->venue_tagline ?? '');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan gedung berhasil disimpan.');
    }
}
