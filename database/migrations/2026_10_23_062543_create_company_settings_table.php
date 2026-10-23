<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('ALFITO Tour & Travel');
            $table->string('short_name')->default('ALFITO');
            $table->string('tagline')->default('Tour & Travel Indonesia');
            $table->text('description')->nullable();
            $table->string('whatsapp_number')->default('081234567890');
            $table->string('phone')->nullable()->default('0812-3456-7890');
            $table->string('email')->nullable()->default('halo@alfito.test');
            $table->text('address')->nullable();
            $table->string('operational_hours')->nullable()->default('08:00 - 22:00 WIB');
            $table->string('license_info')->nullable()->default('Berlisensi Resmi ASITA & HPI');
            $table->string('logo_url')->nullable();
            $table->string('favicon_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->text('footer_text')->nullable();
            $table->json('bank_accounts')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
