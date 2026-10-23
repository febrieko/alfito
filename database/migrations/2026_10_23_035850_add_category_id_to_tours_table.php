<?php

use App\Models\TourCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('destination_id')->constrained('tour_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropForeignIdFor(TourCategory::class);
            $table->dropColumn('category_id');
        });
    }
};
