<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_occupations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('occupation_en');
            $table->string('occupation_th');
            $table->string('occupation_slug');

            $table->unique(['plan_id', 'occupation_slug']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_occupations');
    }
};