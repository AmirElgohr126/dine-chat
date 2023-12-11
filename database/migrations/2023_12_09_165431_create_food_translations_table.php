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
        Schema::create('food_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id')->references('id')->on('foods')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('locale')->index();
            $table->unique(['food_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
