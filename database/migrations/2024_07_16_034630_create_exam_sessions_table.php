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
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('set_id')->constrained()->onDelete('cascade');
            $table->integer('question_count')->default(10);
            $table->integer('mastery_apprentice_change')->default(0);
            $table->integer('mastery_familiar_change')->default(0);
            $table->integer('mastery_proficient_change')->default(0);
            $table->integer('mastery_mastered_change')->default(0);
            $table->json('questions_array')->nullable();
            $table->integer('current_question')->nullable();
            $table->integer('correct_answers')->default(0);
            $table->integer('incorrect_answers')->default(0);
            $table->decimal('grade', 4, 1)->default(0.0);
            $table->date('date_completed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_sessions');
    }
};
