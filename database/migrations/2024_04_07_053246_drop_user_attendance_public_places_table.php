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
        // Drop the user_attendance_public_places table
        Schema::dropIfExists('user_attendance_public_places');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_attendance_public_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('public_place_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
};
