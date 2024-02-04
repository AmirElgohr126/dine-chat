<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('otp_super_admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('super_admin_id')->references('id')->on('super_admins')->onDelete('cascade');
            $table->string('otp');
            $table->timestamp('expires_at');
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_super_admins');
    }
};
