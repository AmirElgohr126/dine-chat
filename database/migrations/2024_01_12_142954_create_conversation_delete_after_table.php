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
        Schema::create('conversation_delete_after', function (Blueprint $table) {
            $table->id();
            $table->integer('period_reservation_deleted_after')->default(1)->nullable();
            $table->enum('period_reservation_unit', ['hour', 'day', 'week', 'month', 'year'])->default('hour')->nullable();
            $table->integer('period_reservation_deleted_after_followers')->default(1)->nullable();
            $table->enum('period_reservation_unit_followers', ['hour', 'day', 'week', 'month', 'year'])->default('hour')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_delete_after');
    }
};
