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
        Schema::create('payments_subscription_restaurant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('restaurant_users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('subscription_id')->references('id')->on('restaurant_packages')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->string('payment_gateway');
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method');
            $table->string('currency')->default('USD');
            $table->string('billing_address')->nullable();
            $table->string('card_last_four_digits')->nullable();
            $table->string('customer_email')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
