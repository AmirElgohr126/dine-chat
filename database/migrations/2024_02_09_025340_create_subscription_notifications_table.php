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
        Schema::create('subscription_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreignId('package_id')->references('id')->on('notification_packages')->onDelete('cascade');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscrption_notifications');
    }
};
