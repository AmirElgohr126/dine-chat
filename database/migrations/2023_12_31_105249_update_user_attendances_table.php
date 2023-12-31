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
        Schema::table('user_attendances', function (Blueprint $table) {
            $table->dropForeign('user_attendances_table_id_foreign');
            $table->dropColumn('table_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_attendances', function (Blueprint $table) {
            //
        });
    }
};
