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
        // add new column to public_places table
        Schema::table('public_places', function (Blueprint $table) {
            // 2 columns of spaces of public places  like 1 km , 2 km , 3 km or 1 m , 2 m , 3 m
            $table->integer('spaces')->after('status')->default(30);
            $table->enum('spaces_unit',['km','m'])->default('m')->after('spaces');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_places', function (Blueprint $table) {
            $table->dropColumn('spaces');
            $table->dropColumn('spaces_unit');
        });
    }
};
