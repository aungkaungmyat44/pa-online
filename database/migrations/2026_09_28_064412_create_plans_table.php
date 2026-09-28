<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_th');

            $table->decimal('death_coverage', 12, 2);
            $table->decimal('assaulted_coverage', 12, 2);
            $table->decimal('vehicle_coverage', 12, 2);
            $table->decimal('medical_expense_coverage', 12, 2);
            $table->decimal('premium_amount', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};