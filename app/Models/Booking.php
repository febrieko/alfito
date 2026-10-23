<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'user_id', 'tour_id', 'travel_date',
        'pax_count', 'total_price', 'status', 'special_request',
        'contact_phone', 'admin_note', 'confirmed_at', 'paid_at',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'total_price' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            $booking->booking_code = 'WK-'.now()->format('Ymd').'-'.str_pad(
                static::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT
            );
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function getWhatsAppUrlAttribute(): string
    {
        $message = urlencode(
            "Halo, saya ingin konfirmasi booking:\n".
            "Kode: {$this->booking_code}\n".
            "Paket: {$this->tour->title}\n".
            "Tanggal: {$this->travel_date->format('d M Y')}\n".
            "Pax: {$this->pax_count} orang\n".
            'Total: Rp '.number_format($this->total_price, 0, ',', '.')
        );

        return "https://wa.me/62YOURPHONE?text={$message}";
    }
}
