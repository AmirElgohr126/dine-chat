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
        Schema::table('tables', function (Blueprint $table) {
            $table->dropUnique('tables_table_number_restaurant_id_unique');
            $table->dropColumn('table_number');
            $table->double('x', 15, 8)->after('id');
            $table->double('y', 15, 8)->after('x');
            $table->string('img')->after('y');
            $table->string('key')->after('img');
            $table->unique(['id', 'restaurant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn(['x', 'y', 'img', 'key']);
        });
    }
};
