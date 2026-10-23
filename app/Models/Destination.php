<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    protected $fillable = [
        'name', 'slug', 'region', 'country', 'description',
        'thumbnail', 'images', 'map_lat', 'map_lng',
        'is_featured', 'view_count',
    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'boolean',
        'map_lat' => 'decimal:7',
        'map_lng' => 'decimal:7',
    ];

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function incrementViews(): void
    {
        $this->increment('view_count');
    }
}
