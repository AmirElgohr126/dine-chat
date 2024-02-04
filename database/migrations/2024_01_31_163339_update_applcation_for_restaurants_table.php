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
        Schema::table('applcation_for_restaurants', function (Blueprint $table) {
            $table->enum('status',['pending','accepted','rejected'])->default('pending');
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applcation_for_restaurants', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('phone');
        });
    }
};
