<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Admin dashboard overview with statistics.
     */
    public function dashboard(Request $request): View
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

        // ── Trend: Hari ini vs kemarin ─────────────────────────
        $today     = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayStats = Booking::selectRaw('
            count(*) as total,
            sum(case when status = "pending"   then 1 else 0 end) as pending_count,
            sum(case when status = "completed" then 1 else 0 end) as completed_count,
            sum(case when status = "cancelled" then 1 else 0 end) as cancelled_count,
            sum(case when status in ("paid","completed") then total_price else 0 end) as revenue
        ')->whereDate('start_time', $today)->first();

        $yestStats = Booking::selectRaw('
            count(*) as total,
            sum(case when status = "pending"   then 1 else 0 end) as pending_count,
            sum(case when status = "completed" then 1 else 0 end) as completed_count,
            sum(case when status = "cancelled" then 1 else 0 end) as cancelled_count,
            sum(case when status in ("paid","completed") then total_price else 0 end) as revenue
        ')->whereDate('start_time', $yesterday)->first();

        // ── Trend: Bulan ini vs bulan lalu (untuk revenue %) ──
        $thisMonthRevenue = Booking::whereIn('status', ['paid', 'completed'])
            ->whereYear('start_time', $today->year)
            ->whereMonth('start_time', $today->month)
            ->sum('total_price');

        $lastMonthRevenue = Booking::whereIn('status', ['paid', 'completed'])
            ->whereYear('start_time', $today->copy()->subMonth()->year)
            ->whereMonth('start_time', $today->copy()->subMonth()->month)
            ->sum('total_price');

        // Helper: hitung delta & persen
        $delta = fn($now, $prev) => (int)$now - (int)$prev;
        $pct   = function ($now, $prev) {
            if ($prev == 0) return $now > 0 ? 100 : 0;
            return round((($now - $prev) / $prev) * 100);
        };

        $trends = [
            'total_bookings' => [
                'delta' => $delta($todayStats->total, $yestStats->total),
                'label' => 'dari kemarin',
            ],
            'pending' => [
                'delta' => $delta($todayStats->pending_count, $yestStats->pending_count),
                'label' => 'dari kemarin',
            ],
            'completed' => [
                'delta' => $delta($todayStats->completed_count, $yestStats->completed_count),
                'label' => 'dari kemarin',
            ],
            'cancelled' => [
                'delta' => $delta($todayStats->cancelled_count, $yestStats->cancelled_count),
                'label' => 'dari kemarin',
            ],
            'total_revenue' => [
                'pct'   => $pct($thisMonthRevenue, $lastMonthRevenue),
                'label' => 'vs bulan lalu',
            ],
        ];

        $recentBookings = Booking::with(['user', 'court'])
            ->latest('id')
            ->take(10)
            ->get();

        // ── Available years for filter dropdown ───────────────
        $availableYears = Booking::selectRaw('YEAR(start_time) as yr')
            ->groupBy('yr')
            ->orderByDesc('yr')
            ->pluck('yr')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        $filterYear  = $request->filled('year')  ? (int) $request->year  : null;
        $filterMonth = $request->filled('month') ? (int) $request->month : null;

        // ── Mode selection ────────────────────────────────────
        if ($filterYear && $filterMonth) {
            // Mode 3: Daily view for a specific month
            $start = Carbon::create($filterYear, $filterMonth, 1)->startOfDay();
            $end   = $start->copy()->endOfMonth();
            $days  = $start->diffInDays($end) + 1;
            $range = collect(range(0, $days - 1))->map(fn($i) => $start->copy()->addDays($i));

            $revenueRaw = Booking::selectRaw('DATE(start_time) as date, SUM(total_price) as total')
                ->whereIn('status', ['paid', 'completed'])
                ->whereBetween('start_time', [$start, $end->copy()->endOfDay()])
                ->groupBy('date')
                ->pluck('total', 'date');

            $bookingsRaw = Booking::selectRaw('DATE(start_time) as date, COUNT(*) as total')
                ->whereBetween('start_time', [$start, $end->copy()->endOfDay()])
                ->groupBy('date')
                ->pluck('total', 'date');

            $chartLabels   = $range->map(fn($d) => $d->format('d'))->values()->toArray();
            $chartRevenue  = $range->map(fn($d) => (float) ($revenueRaw[$d->toDateString()] ?? 0))->values()->toArray();
            $chartBookings = $range->map(fn($d) => (int)   ($bookingsRaw[$d->toDateString()] ?? 0))->values()->toArray();
            $chartPeriodLabel = Carbon::create($filterYear, $filterMonth, 1)->translatedFormat('F Y');

        } elseif ($filterYear) {
            // Mode 2: Monthly view for a specific year
            $months = collect(range(1, 12))->map(fn($m) => Carbon::create($filterYear, $m, 1));

            $revenueRaw = Booking::selectRaw('MONTH(start_time) as month, SUM(total_price) as total')
                ->whereIn('status', ['paid', 'completed'])
                ->whereYear('start_time', $filterYear)
                ->groupBy('month')
                ->pluck('total', 'month');

            $bookingsRaw = Booking::selectRaw('MONTH(start_time) as month, COUNT(*) as total')
                ->whereYear('start_time', $filterYear)
                ->groupBy('month')
                ->pluck('total', 'month');

            $chartLabels   = $months->map(fn($m) => $m->translatedFormat('M'))->values()->toArray();
            $chartRevenue  = $months->map(fn($m) => (float) ($revenueRaw[$m->month] ?? 0))->values()->toArray();
            $chartBookings = $months->map(fn($m) => (int)   ($bookingsRaw[$m->month] ?? 0))->values()->toArray();
            $chartPeriodLabel = 'Tahun ' . $filterYear;

        } else {
            // Mode 1 (default): Last 7 days
            $last7Days = collect(range(6, 0))->map(fn($i) => Carbon::today()->subDays($i));

            $revenueRaw = Booking::selectRaw('DATE(start_time) as date, SUM(total_price) as total')
                ->whereIn('status', ['paid', 'completed'])
                ->where('start_time', '>=', Carbon::today()->subDays(6)->startOfDay())
                ->groupBy('date')
                ->pluck('total', 'date');

            $bookingsRaw = Booking::selectRaw('DATE(start_time) as date, COUNT(*) as total')
                ->where('start_time', '>=', Carbon::today()->subDays(6)->startOfDay())
                ->groupBy('date')
                ->pluck('total', 'date');

            $chartLabels   = $last7Days->map(fn($d) => $d->translatedFormat('D, d M'))->values()->toArray();
            $chartRevenue  = $last7Days->map(fn($d) => (float) ($revenueRaw[$d->toDateString()] ?? 0))->values()->toArray();
            $chartBookings = $last7Days->map(fn($d) => (int)   ($bookingsRaw[$d->toDateString()] ?? 0))->values()->toArray();
            $chartPeriodLabel = '7 Hari Terakhir';
        }

        // ── Booking per Court (always all-time, unaffected by filter) ──
        $courtQuery = Booking::with('court')->selectRaw('court_id, COUNT(*) as total')->groupBy('court_id');
        if ($filterYear)  { $courtQuery->whereYear('start_time', $filterYear); }
        if ($filterMonth) { $courtQuery->whereMonth('start_time', $filterMonth); }

        $courtBookings = $courtQuery->get()
            ->mapWithKeys(fn($b) => [$b->court->name ?? 'Unknown' => $b->total]);

        $chartCourtLabels = $courtBookings->keys()->toArray();
        $chartCourtData   = $courtBookings->values()->toArray();

        return view('admin.dashboard', compact(
            'stats', 'recentBookings', 'trends',
            'chartLabels', 'chartRevenue', 'chartBookings', 'chartPeriodLabel',
            'chartCourtLabels', 'chartCourtData',
            'availableYears', 'filterYear', 'filterMonth'
        ));
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
     * Show the printable receipt for a booking (admin view).
     * Reuses the same receipt view as the customer.
     */
    public function receipt(Booking $booking): View
    {
        $booking->load(['user', 'court']);

        return view('customer.bookings.receipt', compact('booking'));
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
