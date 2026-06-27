<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Customer dashboard: show their own bookings.
     */
    public function index(): View
    {
        Booking::cancelExpiredBookings();

        $bookings = Booking::with('court')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        $courts = Court::active()->get(['id', 'name']);

        return view('customer.dashboard', compact('bookings', 'courts'));
    }

    /**
     * Show the booking form with all active courts.
     */
    public function create(): View
    {
        $courts = Court::active()->get();

        return view('customer.bookings.create', compact('courts'));
    }

    /**
     * Store a new booking.
     *
     * Business Logic:
     * 1. StoreBookingRequest handles overlap validation (Step 2 core logic).
     * 2. total_price is calculated strictly on the backend — never trusted from input.
     * 3. Status is always set to 'pending' on creation.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $court     = Court::findOrFail($request->court_id);
        $startTime = $request->start_time;
        $endTime   = $request->end_time;

        // AUTO-MATH: Calculate total_price on the backend
        $start      = \Carbon\Carbon::parse($startTime);
        $end        = \Carbon\Carbon::parse($endTime);
        $hours      = abs($start->diffInMinutes($end)) / 60; // supports half-hour slots, always positive
        $totalPrice = round($hours * $court->price_per_hour, 2);

        $booking = Booking::create([
            'user_id'     => auth()->id(),
            'court_id'    => $court->id,
            'start_time'  => $startTime,
            'end_time'    => $endTime,
            'total_price' => $totalPrice,
            'status'      => 'pending',
        ]);

        // Xendit Integration
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
        $apiInstance = new InvoiceApi();

        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id' => 'BOOKING_' . $booking->id . '_' . Str::random(5),
            'description' => 'Pembayaran Booking Lapangan ' . $court->name,
            'amount' => $totalPrice,
            'payer_email' => auth()->user()->email,
            'customer' => [
                'given_names' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
            'success_redirect_url' => route('customer.bookings.show', $booking->id),
            'failure_redirect_url' => route('customer.dashboard'),
        ]);

        try {
            $result = $apiInstance->createInvoice($createInvoiceRequest);
            
            $booking->update([
                'xendit_invoice_id' => $result['id'],
                'payment_url' => $result['invoice_url']
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Xendit Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            // If Xendit fails, we can delete the booking or just leave it pending without payment URL
            // and let the user try again, or return error.
            $booking->delete();
            return redirect()->route('customer.dashboard')
                ->with('error', 'Gagal membuat tagihan pembayaran. Silakan coba lagi.');
        }

        return redirect()->route('customer.dashboard')
            ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran melalui invoice.');
    }

    /**
     * Show a single booking detail (customer can only see their own).
     */
    public function show(Booking $booking): View
    {
        // Policy: customer can only view their own booking
        abort_if($booking->user_id !== auth()->id(), 403);

        return view('customer.bookings.show', compact('booking'));
    }

    /**
     * Printable payment receipt for a booking.
     */
    public function receipt(Booking $booking): View
    {
        abort_if($booking->user_id !== auth()->id(), 403);

        $booking->load(['court', 'user']);

        return view('customer.bookings.receipt', compact('booking'));
    }

    /**
     * Remove or adapt simulated payment if needed.
     * We will remove the simulated payment and rely on webhook.
     */

    /**
     * Check payment status manually from Xendit API.
     * Useful for local development when webhooks cannot reach localhost.
     */
    public function checkStatus(Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if(!$booking->xendit_invoice_id, 422, 'Booking ini tidak memiliki invoice Xendit.');

        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
        $apiInstance = new InvoiceApi();

        try {
            $invoice = $apiInstance->getInvoiceById($booking->xendit_invoice_id);
            $status = $invoice['status'] ?? null;

            if ($status === 'PAID' || $status === 'SETTLED') {
                $booking->update(['status' => 'completed']);
                return back()->with('success', 'Pembayaran berhasil dikonfirmasi dari Xendit!');
            } elseif ($status === 'EXPIRED') {
                $booking->update(['status' => 'cancelled']);
                return back()->with('error', 'Waktu pembayaran telah habis.');
            }

            return back()->with('info', 'Pembayaran belum diterima oleh Xendit. Silakan selesaikan pembayaran terlebih dahulu.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Xendit Check Status Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengecek status ke Xendit.');
        }
    }

    /**
     * Customer cancels their own pending booking.
     * Only 'pending' bookings can be cancelled.
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if($booking->status !== 'pending', 422, 'Hanya booking dengan status pending yang dapat dibatalkan.');

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
