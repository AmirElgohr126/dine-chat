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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->references('id')->on('users')->onDelete('NO ACTION');
            $table->foreignId('receiver_id')->references('id')->on('users')->onDelete('NO ACTION');
            $table->foreignId('restaurant_id')->references('id')->on('restaurants')->onDelete('NO ACTION');
            $table->enum('status',['accept','reject','invited']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
