<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_unique_code')->unique();

            $table->foreignId('plan_id')->constrained()->restrictOnDelete();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();

            $table->string('customer_id_type')->nullable();
            $table->string('customer_id_number')->nullable();
            $table->string('agent_no')->nullable();
            $table->string('policy_no')->nullable()->unique();

            $table->json('order_info')->nullable();
            $table->json('health_question_answers')->nullable();

            $table->date('effective_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->string('status', 30)->default('draft');

            $table->decimal('premium_amount', 12, 2)->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->decimal('vat', 12, 2)->default(0);
            $table->decimal('duty', 12, 2)->default(0);

            $table->string('payment_method', 30)->nullable();
            $table->string('payment_status', 30)->default('unpaid');
            $table->timestamp('paid_at')->nullable();

            $table->boolean('is_email_sent')->default(false);
            $table->text('policy_url')->nullable();
            $table->string('barcode_no')->nullable();
            $table->boolean('is_policy_generated')->default(false);

            $table->json('save_data_result')->nullable();
            $table->json('issue_policy_result')->nullable();

            $table->timestamps();

            $table->index(['customer_id', 'created_at']);
            $table->index(['payment_status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};