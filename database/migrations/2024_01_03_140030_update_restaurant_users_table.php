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
        Schema::table('restaurant_users',function(Blueprint $table){
            $table->timestamp('start_subscription');
            $table->timestamp('expire_subscription');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_users', function (Blueprint $table) {
            $table->dropColumn('expire_subscription');
            $table->dropColumn('start_subscription');
        });
    }
};
