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
        Schema::create('user_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->references('id')->on('restaurants')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('chair_id')->nullable()->references('id')->on('chairs')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('public_place_id')->nullable()->references('id')->on('public_places')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->unique(['restaurant_id', 'chair_id', 'user_id','public_place_id'], 'user_attendance_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_attendances');
    }
};
