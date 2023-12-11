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
        Schema::create('chairs', function (Blueprint $table) {
            $table->id();
            $table->integer('chair_number')->nullable(false);
            $table->foreignId('table_id')->references('id')->on('tables')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('restaurant_id')->references('id')->on('restaurants')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unique(['chair_number','table_id','restaurant_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chairs');
    }
};
