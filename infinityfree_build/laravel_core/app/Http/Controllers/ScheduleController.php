<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Return all active bookings as FullCalendar-compatible JSON events.
     *
     * Query params:
     *   - court_id (optional): filter by a specific court
     *   - start    (optional): range start date (ISO8601) — provided by FullCalendar
     *   - end      (optional): range end date (ISO8601)  — provided by FullCalendar
     */
    public function events(Request $request): JsonResponse
    {
        Booking::cancelExpiredBookings();

        $query = Booking::with(['court', 'user'])
            ->whereIn('status', ['pending', 'paid', 'completed']);

        // Filter per court
        if ($request->filled('court_id')) {
            $query->where('court_id', $request->court_id);
        }

        // Filter by date range (FullCalendar sends start & end when navigating)
        if ($request->filled('start')) {
            $query->where('end_time', '>=', $request->start);
        }
        if ($request->filled('end')) {
            $query->where('start_time', '<=', $request->end);
        }

        $bookings = $query->get();

        $events = $bookings->flatMap(function (Booking $booking) {
            $statusColors = [
                'pending'   => '#f59e0b', // amber
                'paid'      => '#3b82f6', // blue
                'completed' => '#10b981', // emerald
            ];

            $statusLabels = [
                'pending'   => 'Menunggu Bayar',
                'paid'      => 'Terkonfirmasi',
                'completed' => 'Selesai',
            ];

            $color = $statusColors[$booking->status] ?? '#6b7280';
            $label = $statusLabels[$booking->status] ?? $booking->status;

            $baseEvent = [
                'id'         => $booking->id,
                'title'      => $booking->court->name,
                'color'      => $color,
                'extendedProps' => [
                    'court'    => $booking->court->name,
                    'user'     => $booking->user->name ?? 'Tamu',
                    'status'   => $label,
                    'start'    => $booking->start_time->format('H:i'),
                    'end'      => $booking->end_time->format('H:i'),
                    'date'     => $booking->start_time->translatedFormat('d M Y'),
                ],
            ];

            // Jika booking melintasi tengah malam (beda hari)
            // Kasus 1: Berakhir tepat di 00:00 hari berikutnya (misal 23:00 - 00:00)
            if (!$booking->start_time->isSameDay($booking->end_time) && $booking->end_time->format('H:i:s') === '00:00:00') {
                $baseEvent['start'] = $booking->start_time->toIso8601String();
                // Kurangi 1 detik agar FullCalendar tidak merendernya di hari berikutnya
                $baseEvent['end']   = $booking->end_time->copy()->subSecond()->toIso8601String();
                return [$baseEvent];
            }
            
            // Kasus 2: Benar-benar melintasi hari (misal 23:00 - 01:00)
            if (!$booking->start_time->isSameDay($booking->end_time)) {
                $event1 = $baseEvent;
                $event1['start'] = $booking->start_time->toIso8601String();
                $event1['end']   = $booking->start_time->copy()->endOfDay()->toIso8601String(); // 23:59:59
                
                $event2 = $baseEvent;
                $event2['extendedProps']['date'] = $booking->end_time->translatedFormat('d M Y');
                $event2['start'] = $booking->end_time->copy()->startOfDay()->toIso8601String(); // 00:00:00
                $event2['end']   = $booking->end_time->toIso8601String();

                return [$event1, $event2];
            }

            // Kasus 3: Di hari yang sama
            $baseEvent['start'] = $booking->start_time->toIso8601String();
            $baseEvent['end']   = $booking->end_time->toIso8601String();
            
            return [$baseEvent];
        });

        return response()->json($events->values());
    }

    /**
     * Return all courts for the schedule page (used by landing & customer dashboard).
     */
    public function courts(): JsonResponse
    {
        $courts = Court::getActiveCached(['id', 'name']);
        return response()->json($courts);
    }
}
