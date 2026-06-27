<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourtController extends Controller
{
    /**
     * List all courts (admin view).
     */
    public function index(): View
    {
        $courts = Court::latest()->paginate(10);

        return view('admin.courts.index', compact('courts'));
    }

    /**
     * Show form to create a new court.
     */
    public function create(): View
    {
        return view('admin.courts.create');
    }

    /**
     * Show a single court detail (admin view).
     */
    public function show(Court $court): View
    {
        return view('admin.courts.edit', compact('court'));
    }

    /**
     * Store a new court.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'price_per_hour' => ['required', 'numeric', 'min:1000'],
            'facilities'     => ['nullable', 'array'],
            'facilities.*'   => ['string', 'max:100'],
            'is_active'      => ['boolean'],
        ]);

        // Ensure boolean field is always set (checkbox may not send value when unchecked)
        $validated['is_active'] = $request->boolean('is_active', true);

        Court::create($validated);

        return redirect()->route('admin.courts.index')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    /**
     * Show form to edit an existing court.
     */
    public function edit(Court $court): View
    {
        return view('admin.courts.edit', compact('court'));
    }

    /**
     * Update an existing court.
     */
    public function update(Request $request, Court $court): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'price_per_hour' => ['required', 'numeric', 'min:1000'],
            'facilities'     => ['nullable', 'array'],
            'facilities.*'   => ['string', 'max:100'],
            'is_active'      => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $court->update($validated);

        return redirect()->route('admin.courts.index')
            ->with('success', 'Data lapangan berhasil diperbarui.');
    }

    /**
     * Soft-deactivate (toggle) or permanently delete a court.
     * We use deactivate to protect historical booking records.
     */
    public function destroy(Court $court): RedirectResponse
    {
        // Prevent deletion if there are active bookings tied to this court
        $hasActiveBookings = $court->bookings()
            ->whereIn('status', ['pending', 'paid'])
            ->exists();

        if ($hasActiveBookings) {
            return redirect()->route('admin.courts.index')
                ->with('error', 'Lapangan tidak bisa dihapus karena masih ada booking aktif.');
        }

        $court->delete();

        return redirect()->route('admin.courts.index')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
}
