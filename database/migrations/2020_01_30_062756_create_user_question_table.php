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
        Schema::create('user_question', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('question_id');
            $table->bigInteger('set_id');
            $table->tinyInteger('score');
            $table->dateTime('next_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_question');
    }
};
