<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                ->constrained('health_questions')
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('sort_order');
            $table->text('answer_text');

            $table->unique(['question_id', 'sort_order']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_question_answers');
    }
};