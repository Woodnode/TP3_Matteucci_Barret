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
        Schema::create('film_statistics', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('film_id')->constrained('films')->cascadeOnDelete();
            $table->unsignedInteger('total_reviews')->default(0);
            $table->decimal('average_score', 5, 2)->default(0);
            $table->unsignedInteger('external_reviews')->default(0);
            $table->decimal('external_average_score', 5, 2)->default(0);
            $table->unique('film_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film_statistics');
    }
};
