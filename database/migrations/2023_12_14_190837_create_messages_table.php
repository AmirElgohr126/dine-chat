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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->references('id')->on('conversations')->onDelete('NO ACTION');
            $table->foreignId('sender_id')->references('id')->on('users')->onDelete('NO ACTION');
            $table->text('content')->nullable();
            $table->string('attachment')->nullable();
            $table->foreignId('receiver_id')->references('id')->on('users')->onDelete('NO ACTION');
            $table->foreignId('replay_on')->references('id')->on('messages')->onDelete('NO ACTION');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
