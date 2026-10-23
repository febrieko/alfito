<?php

namespace App\Providers;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                $company = CompanySetting::get();
            } catch (\Throwable) {
                $company = new CompanySetting([
                    'company_name' => 'ALFITO Tour & Travel',
                    'short_name' => 'ALFITO',
                    'tagline' => 'Tour & Travel Indonesia',
                    'whatsapp_number' => '081234567890',
                    'phone' => '0812-3456-7890',
                    'email' => 'halo@alfito.test',
                    'operational_hours' => '08:00 - 22:00 WIB',
                    'address' => 'Jl. Danau Toba No. 45, Sawojajar, Kota Malang, Jawa Timur',
                ]);
            }

            $view->with('company', $company);
        });
    }
}
