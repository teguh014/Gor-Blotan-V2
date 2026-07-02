<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman laporan keuangan.
     */
    public function index(Request $request): View
    {
        // Default filter ke bulan ini (format YYYY-MM)
        $month = $request->input('month', now()->format('Y-m'));

        // Parse tahun dan bulan dari input
        try {
            $date = Carbon::createFromFormat('Y-m', $month);
            $filterYear = $date->year;
            $filterMonth = $date->month;
        } catch (\Exception $e) {
            $filterYear = now()->year;
            $filterMonth = now()->month;
            $month = now()->format('Y-m');
        }

        // Ambil data transaksi yang sudah dibayar atau selesai
        $query = Booking::with(['court', 'user'])
            ->whereIn('status', ['paid', 'completed'])
            ->whereYear('start_time', $filterYear)
            ->whereMonth('start_time', $filterMonth);

        // Hitung metrik
        $totalRevenue = $query->sum('total_price');
        $totalBookings = $query->count();
        
        // Ambil detail transaksi
        $bookings = $query->latest('id')->get();

        return view('admin.reports.index', compact(
            'bookings', 
            'totalRevenue', 
            'totalBookings', 
            'month'
        ));
    }
}
