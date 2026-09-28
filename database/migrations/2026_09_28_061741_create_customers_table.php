<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();

            $table->string('otp_code')->nullable(); // Hashed OTP
            $table->boolean('is_otp_sent')->default(false);
            $table->timestamp('otp_expires_at')->nullable();
            $table->unsignedTinyInteger('otp_attempts')->default(0);
            $table->timestamp('otp_verified_at')->nullable();

            $table->boolean('is_activated')->default(false);
            $table->string('status', 20)->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};