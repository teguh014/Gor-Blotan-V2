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

        $bookingStats = Booking::selectRaw('
            count(*) as total,
            sum(case when status = "pending" then 1 else 0 end) as pending_count,
            sum(case when status = "paid" then 1 else 0 end) as paid_count,
            sum(case when status = "completed" then 1 else 0 end) as completed_count,
            sum(case when status = "cancelled" then 1 else 0 end) as cancelled_count,
            sum(case when status in ("paid", "completed") then total_price else 0 end) as total_revenue
        ')->first();

        $stats = [
            'total_bookings'   => (int) ($bookingStats->total ?? 0),
            'pending'          => (int) ($bookingStats->pending_count ?? 0),
            'paid'             => (int) ($bookingStats->paid_count ?? 0),
            'completed'        => (int) ($bookingStats->completed_count ?? 0),
            'cancelled'        => (int) ($bookingStats->cancelled_count ?? 0),
            'total_revenue'    => (float) ($bookingStats->total_revenue ?? 0),
            'total_courts'     => Court::count(),
            'total_customers'  => User::where('role', 'customer')->count(),
        ];

        $recentBookings = Booking::with(['user', 'court'])
            ->latest('id')
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

        $query = Booking::with(['user', 'court'])->latest('id');

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
