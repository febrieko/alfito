<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'short_name',
        'tagline',
        'description',
        'whatsapp_number',
        'phone',
        'email',
        'address',
        'operational_hours',
        'license_info',
        'logo_url',
        'favicon_url',
        'instagram_url',
        'threads_url',
        'facebook_url',
        'tiktok_url',
        'youtube_url',
        'footer_text',
        'bank_accounts',
    ];

    protected $casts = [
        'bank_accounts' => 'array',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('company_settings');
        });

        static::deleted(function () {
            Cache::forget('company_settings');
        });
    }

    /**
     * Get the singleton company settings instance, cached safely.
     */
    public static function get(): self
    {
        try {
            $cached = Cache::get('company_settings');
            if ($cached instanceof self) {
                return $cached;
            }
            Cache::forget('company_settings');
        } catch (\Throwable) {
            Cache::forget('company_settings');
        }

        try {
            $setting = static::first();
        } catch (\Throwable) {
            $setting = null;
        }

        if (! $setting) {
            $setting = static::createDefault();
        }

        try {
            Cache::put('company_settings', $setting, 86400);
        } catch (\Throwable) {
            // Ignore cache errors
        }

        return $setting;
    }

    /**
     * Create default company settings record if none exists.
     */
    public static function createDefault(): self
    {
        $defaults = [
            'company_name' => 'ALFITO Tour & Travel',
            'short_name' => 'ALFITO',
            'tagline' => 'Tour & Travel Indonesia',
            'description' => 'Platform tour operator resmi terkemuka di Indonesia. Mengkhususkan diri pada paket Gathering Kantor, Open Trip, dan Private Trip Bromo, Labuan Bajo Komodo, Raja Ampat, dan destinasi favorit Nusantara dengan standar pelayanan bintang 5.',
            'whatsapp_number' => '081234567890',
            'phone' => '0812-3456-7890',
            'email' => 'halo@alfito.test',
            'address' => 'Jl. Danau Toba No. 45, Sawojajar, Kota Malang, Jawa Timur 65139',
            'operational_hours' => '08:00 - 22:00 WIB',
            'license_info' => 'Berlisensi Resmi ASITA & HPI',
            'instagram_url' => 'https://instagram.com/alfito.travel',
            'threads_url' => 'https://threads.net/@alfito.travel',
            'facebook_url' => 'https://facebook.com/alfito.travel',
            'tiktok_url' => 'https://tiktok.com/@alfito.travel',
            'youtube_url' => 'https://youtube.com/@alfitotravel',
            'footer_text' => 'All rights reserved. Platform Tour & Travel Terpercaya di Indonesia.',
            'bank_accounts' => [
                ['bank' => 'BCA', 'account_number' => '123-456-7890', 'account_name' => 'PT ALFITO WISATA NUSANTARA'],
                ['bank' => 'Mandiri', 'account_number' => '144-00-1234567-8', 'account_name' => 'PT ALFITO WISATA NUSANTARA'],
                ['bank' => 'BNI', 'account_number' => '098-765-4321', 'account_name' => 'PT ALFITO WISATA NUSANTARA'],
            ],
        ];

        try {
            return static::create($defaults);
        } catch (\Throwable) {
            return new static($defaults);
        }
    }

    /**
     * Clean phone digits to international WhatsApp format (e.g. 6281234567890).
     */
    public function getCleanWhatsappAttribute(): string
    {
        $raw = preg_replace('/[^0-9]/', '', (string) ($this->whatsapp_number ?? '081234567890'));

        if (str_starts_with($raw, '0')) {
            return '62'.substr($raw, 1);
        }

        if (str_starts_with($raw, '8')) {
            return '62'.$raw;
        }

        return $raw ?: '6281234567890';
    }

    /**
     * Generate dynamic WhatsApp chat URL with optional prefilled message.
     */
    public function getWhatsappUrl(?string $message = null): string
    {
        $phone = $this->clean_whatsapp;
        $defaultMsg = "Halo {$this->short_name}, saya tertarik dengan informasi paket wisata.";
        $text = urlencode($message ?: $defaultMsg);

        return "https://wa.me/{$phone}?text={$text}";
    }

    /**
     * Get effective favicon URL (uses company logo or uploaded favicon, with SVG fallback).
     */
    public function getEffectiveFaviconUrlAttribute(): string
    {
        if (! empty($this->logo_url)) {
            return $this->logo_url;
        }

        if (! empty($this->favicon_url)) {
            return $this->favicon_url;
        }

        return 'data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🧭</text></svg>';
    }
}
