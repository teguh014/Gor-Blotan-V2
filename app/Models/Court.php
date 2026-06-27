<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'price_per_hour',
        'facilities',
        'is_active',
    ];

    /**
     * Attribute casting.
     * - facilities: JSON column auto-casted to PHP array
     * - price_per_hour: always returned as a 2-decimal string
     * - is_active: always boolean
     */
    protected function casts(): array
    {
        return [
            'facilities'     => 'array',
            'price_per_hour' => 'decimal:2',
            'is_active'      => 'boolean',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /**
     * A court can have many bookings.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Only return courts that are currently active and open for booking.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
