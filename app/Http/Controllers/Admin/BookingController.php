<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Admin dashboard overview with statistics.
     */
    public function dashboard(): View
    {
        Booking::cancelExpiredBookings();

        $stats = [
            'total_bookings'   => Booking::count(),
            'pending'          => Booking::where('status', 'pending')->count(),
            'paid'             => Booking::where('status', 'paid')->count(),
            'completed'        => Booking::where('status', 'completed')->count(),
            'cancelled'        => Booking::where('status', 'cancelled')->count(),
            'total_revenue'    => Booking::where('status', 'completed')->sum('total_price'),
            'total_courts'     => Court::count(),
            'total_customers'  => User::where('role', 'customer')->count(),
        ];

        $recentBookings = Booking::with(['user', 'court'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }

    /**
     * List all bookings with filters.
     */
    public function index(Request $request): View
    {
        Booking::cancelExpiredBookings();

        $query = Booking::with(['user', 'court'])->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by court
        if ($request->filled('court_id')) {
            $query->where('court_id', $request->court_id);
        }

        $bookings = $query->paginate(15)->withQueryString();
        $courts   = Court::all();

        return view('admin.bookings.index', compact('bookings', 'courts'));
    }

    /**
     * Show a single booking detail (admin view).
     */
    public function show(Booking $booking): View
    {
        $booking->load(['user', 'court']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * ADMIN FLOW: Mark a 'paid' booking as 'completed'.
     * This is the final step in the booking lifecycle:
     *   pending → paid → completed
     */
    public function complete(Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'paid') {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Hanya booking berstatus "paid" yang bisa diselesaikan.');
        }

        $booking->update(['status' => 'completed']);

        return redirect()->route('admin.bookings.index')
            ->with('success', "Booking #{$booking->id} berhasil diselesaikan.");
    }

    /**
     * Admin can forcefully cancel any non-completed booking.
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        if ($booking->status === 'completed') {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Booking yang sudah selesai tidak bisa dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('admin.bookings.index')
            ->with('success', "Booking #{$booking->id} berhasil dibatalkan.");
    }

    /**
     * Delete a booking permanently from the database.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        $id = $booking->id;
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', "Transaksi Booking #{$id} berhasil dihapus secara permanen.");
    }
}
