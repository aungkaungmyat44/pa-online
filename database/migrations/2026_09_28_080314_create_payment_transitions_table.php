<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transitions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();

            $table->string('reference_no')->unique();
            $table->string('charge_id')->nullable();
            $table->string('qr_id')->nullable();
            $table->string('link_ref')->nullable();

            $table->string('provider', 50);
            $table->string('method', 30);
            $table->string('status', 30)->default('pending');
            $table->string('provider_status', 50)->nullable();

            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('THB');

            $table->json('payment_create_info')->nullable();
            $table->json('inquiry_data_result')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'created_at']);
            $table->index(['customer_id', 'created_at']);
            $table->index(['provider', 'charge_id']);
            $table->index(['provider', 'qr_id']);
            $table->index(['provider', 'link_ref']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transitions');
    }
};