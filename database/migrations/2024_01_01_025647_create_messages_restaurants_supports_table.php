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
        Schema::create('messages_restaurants_supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->references('id')->on('tickets')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('message');
            $table->enum('replay',[0,1])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages_restaurants_supports');
    }
};
