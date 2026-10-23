<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // agen
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['open_trip', 'private_trip'])->default('open_trip');
            $table->integer('duration_days');
            $table->integer('duration_nights');
            $table->decimal('price', 12, 2);
            $table->decimal('price_private', 12, 2)->nullable();
            $table->integer('min_pax')->default(1);
            $table->integer('max_pax')->default(20);
            $table->text('description');
            $table->json('itinerary')->nullable(); // [{day:1, title:'', activities:[]}]
            $table->json('includes')->nullable(); // ['Transportasi', 'Makan', ...]
            $table->json('excludes')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
