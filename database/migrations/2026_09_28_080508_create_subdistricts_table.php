<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subdistricts_by_oic', function (Blueprint $table) {
            $table->id();

            $table->string('code', 6)->unique();
            $table->string('name')->nullable();
            $table->string('name_en')->nullable();

            $table->string('province_code', 10)->nullable();
            $table->string('province_number', 2)->nullable();

            $table->string('district_code', 2)->nullable();
            $table->string('district_name')->nullable();
            $table->string('district_name_en')->nullable();

            $table->string('subdistrict_code', 2)->nullable();
            $table->string('subdistrict_name')->nullable();
            $table->string('subdistrict_name_en')->nullable();

            $table->timestamps();

            $table->index(['province_number', 'district_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subdistricts_by_oic');
    }
};