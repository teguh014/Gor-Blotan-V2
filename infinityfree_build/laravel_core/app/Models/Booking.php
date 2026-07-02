<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'court_id',
        'start_time',
        'end_time',
        'total_price',
        'status',
        'xendit_invoice_id',
        'payment_url',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'start_time'  => 'datetime',
            'end_time'    => 'datetime',
            'total_price' => 'decimal:2',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /**
     * A booking belongs to a user (customer).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A booking belongs to a court.
     */
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Bookings that are "active" and block a timeslot.
     * Cancelled bookings are excluded (they free up the slot).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'paid', 'completed']);
    }

    /**
     * CORE BUSINESS RULE: Overlap detection scope.
     *
     * Returns bookings for a given court whose time range overlaps
     * with the requested [startTime, endTime] interval.
     *
     * Overlap formula: existing.start_time < new.end_time
     *               AND existing.end_time   > new.start_time
     *
     * Only 'pending', 'paid', and 'completed' bookings are considered blocking.
     * 'cancelled' statuses do NOT block.
     *
     * Usage:
     *   Booking::overlapping($courtId, $startTime, $endTime)->exists()
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int    $courtId
     * @param  string $startTime  e.g. '2024-07-01 08:00:00'
     * @param  string $endTime    e.g. '2024-07-01 10:00:00'
     */
    public function scopeOverlapping($query, int $courtId, string $startTime, string $endTime)
    {
        return $query->where('court_id', $courtId)
                     ->whereIn('status', ['pending', 'paid', 'completed'])
                     ->where(function ($q) use ($startTime, $endTime) {
                         $q->where('start_time', '<', $endTime)
                           ->where('end_time', '>', $startTime);
                     });
    }

    // ── Business Logic Helpers ────────────────────────────────────────────────

    /**
     * Otomatis batalkan booking yang berstatus pending dan usianya lebih dari 30 menit.
     */
    public static function cancelExpiredBookings(): void
    {
        self::where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(30))
            ->update(['status' => 'cancelled']);
    }
}
