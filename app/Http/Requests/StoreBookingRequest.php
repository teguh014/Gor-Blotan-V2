<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Only authenticated customers can make bookings.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isCustomer();
    }

    /**
     * Validation rules for creating a new booking.
     */
    public function rules(): array
    {
        return [
            'booking_type' => ['required', 'in:daily,monthly'],
            'court_id'   => ['required', 'integer', 'exists:courts,id'],
            'start_time' => ['required', 'date_format:Y-m-d\TH:i', 'after:now'],
            'end_time'   => ['required', 'date_format:Y-m-d\TH:i', 'after:start_time'],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'court_id.required'   => 'Lapangan harus dipilih.',
            'court_id.exists'     => 'Lapangan yang dipilih tidak valid.',
            'start_time.required' => 'Waktu mulai harus diisi.',
            'start_time.after'    => 'Waktu mulai harus di masa depan.',
            'end_time.required'   => 'Waktu selesai harus diisi.',
            'end_time.after'      => 'Waktu selesai harus setelah waktu mulai.',
        ];
    }

    /**
     * CORE BUSINESS RULE: Overlap check runs AFTER standard validation passes.
     *
     * This uses Booking::scopeOverlapping() to detect if any 'pending' or 'paid'
     * booking already exists for this court in the requested time range.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Only run if base validation passed (no point checking if dates are invalid)
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $startTime = $this->input('start_time');
            $endTime   = $this->input('end_time');
            $courtId   = (int) $this->input('court_id');

            $start = \Carbon\Carbon::parse($startTime);
            $end   = \Carbon\Carbon::parse($endTime);

            // Validasi per jam penuh
            if ($start->minute !== 0 || $end->minute !== 0) {
                $validator->errors()->add('start_time', 'Booking hanya bisa dilakukan per jam penuh (contoh: 10:00).');
                return;
            }

            // Validasi jam tutup (01:00 - 06:00)
            for ($h = $start->copy(); $h->lt($end); $h->addHour()) {
                if ($h->hour >= 1 && $h->hour < 6) {
                    $validator->errors()->add('start_time', 'Maaf, lapangan tutup pada jam 01:00 - 06:00.');
                    return;
                }
            }

            // Exclude current booking ID when editing (for future update support)
            $excludeId = $this->route('booking')?->id;
            
            $bookingType = $this->input('booking_type', 'daily');
            $weeksToBook = $bookingType === 'monthly' ? 4 : 1;

            for ($i = 0; $i < $weeksToBook; $i++) {
                $checkStart = $start->copy()->addWeeks($i)->format('Y-m-d H:i');
                $checkEnd   = $end->copy()->addWeeks($i)->format('Y-m-d H:i');

                $overlap = Booking::overlapping($courtId, $checkStart, $checkEnd)
                    ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                    ->exists();

                if ($overlap) {
                    $tanggal = \Carbon\Carbon::parse($checkStart)->translatedFormat('d M Y');
                    $validator->errors()->add(
                        'start_time',
                        "Lapangan sudah dipesan pada jam tersebut untuk tanggal {$tanggal}. Silakan pilih jam atau lapangan lain."
                    );
                    return;
                }
            }
        });
    }
}
