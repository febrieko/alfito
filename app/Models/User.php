<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'phone', 'avatar', 'bio',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ─── Role & Status Helpers ────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        if (! empty($this->avatar)) {
            return str_starts_with($this->avatar, 'http')
                ? $this->avatar
                : asset(ltrim($this->avatar, '/'));
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=0ea5e9&color=ffffff&bold=true';
    }

    public function isActive(): bool
    {
        return (bool) ($this->is_active ?? true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAgen(): bool
    {
        return $this->role === 'agen';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // ─── Relationships ───────────────────────────────────────────

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
