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
        Schema::table('chairs', function (Blueprint $table) {
            $table->dropUnique('chairs_chair_number_table_id_restaurant_id_unique'); // +
            $table->dropForeign('chairs_table_id_foreign'); // +
            $table->dropColumn('chair_number'); // +
            $table->dropColumn('table_id'); // +
            $table->float('x',15, 8);
            $table->float('y',15, 8);
            $table->string('img');
            $table->string('key');
            $table->unique(['id','restaurant_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chairs', function (Blueprint $table) {
            $table->dropColumn('x');
            $table->dropColumn('y');
            $table->dropColumn('img');
            $table->dropColumn('key');
        });
    }
};
