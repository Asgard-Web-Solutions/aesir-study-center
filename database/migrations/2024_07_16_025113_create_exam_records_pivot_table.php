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
        Schema::create('exam_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('set_id')->constrained()->onDelete('cascade');
            $table->integer('times_taken')->nullable();
            $table->decimal('recent_average', 4, 1)->nullable();
            $table->string('recent_scores')->nullable();
            $table->integer('mastery_apprentice_count')->nullable();
            $table->integer('mastery_familiar_count')->nullable();
            $table->integer('mastery_proficient_count')->nullable();
            $table->integer('mastery_mastered_count')->nullable();
            $table->date('last_completed')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_records');
    }
};
