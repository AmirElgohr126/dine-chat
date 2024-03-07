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
        Schema::table('booking_dates', function (Blueprint $table) {
            $table->integer('period_logout_public_places')->default(0);
            $table->enum('period_logout_unit_public_places',['hour', 'day', 'week', 'month', 'year'])->default('hour')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
