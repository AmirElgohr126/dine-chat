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
        Schema::table('conversations', function (Blueprint $table) {
            // add column public_place_id
            $table->foreignId('public_place_id')->nullable()->after('restaurant_id')->references('id')->on('public_places')->cascadeOnDelete()->cascadeOnUpdate();
            // make restaurant_id nullable
            $table->foreignId('restaurant_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('public_place_id');
            $table->foreignId('restaurant_id')->nullable(false)->change();
        });
    }
};
