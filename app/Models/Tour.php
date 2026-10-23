<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Tour extends Model
{
    protected $fillable = [
        'user_id', 'destination_id', 'category_id', 'title', 'slug', 'type',
        'duration_days', 'duration_nights', 'price', 'price_private',
        'min_pax', 'max_pax', 'description', 'itinerary',
        'includes', 'excludes', 'thumbnail', 'images',
        'is_featured', 'is_active', 'view_count',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'itinerary' => 'array',
        'includes' => 'array',
        'excludes' => 'array',
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'price_private' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'category_id');
    }

    public function agen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->where('is_approved', true)->avg('rating') ?? 0, 1);
    }

    /**
     * Get unique combined list of thumbnail and gallery images.
     */
    public function getGalleryImagesAttribute(): Collection
    {
        return collect([$this->thumbnail])
            ->merge($this->images ?? [])
            ->filter()
            ->unique()
            ->values();
    }

    public function incrementViews(): void
    {
        $this->increment('view_count');
    }
}
