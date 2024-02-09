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
        Schema::create('restaurant_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo')->default('')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price_per_month', 10, 2)->default(0.00);
            $table->decimal('price_per_year', 10, 2)->default(0.00);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('period_finished_after');
            $table->json('features')->nullable();
            $table->json('limitations')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_packages');
    }
};
