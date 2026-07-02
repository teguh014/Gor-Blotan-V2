<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Court extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'photo_path',
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

    // ── Accessors ─────────────────────────────────────────────────────────────

    /**
     * Get the full URL to the court's photo, or a placeholder if none exists.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }
        
        // Placeholder with court name initials
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=10b981&color=ffffff&size=300&font-size=0.4&bold=true";
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

    // ── Caching ───────────────────────────────────────────────────────────────

    /**
     * Get active courts from cache or database.
     */
    public static function getActiveCached($columns = ['*'])
    {
        $cacheKey = 'active_courts_' . implode('_', $columns);
        
        return Cache::rememberForever($cacheKey, function () use ($columns) {
            return self::active()->get($columns);
        });
    }

    /**
     * Clear the cache when courts are created, updated, or deleted.
     */
    protected static function booted()
    {
        $clearCache = function () {
            // We use a prefix approach for varied column caches, 
            // but for simplicity we will just flush the known combinations,
            // or we could use tags (if redis/memcached is used). 
            // Since default is file, we flush specific keys used in the app.
            Cache::forget('active_courts_*'); 
            Cache::forget('active_courts_id_name');
            Cache::forget('active_courts_id_name_description_price_per_hour_facilities_is_active');
        };

        static::created($clearCache);
        static::updated($clearCache);
        static::deleted($clearCache);
    }
}
