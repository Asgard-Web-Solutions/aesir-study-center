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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->decimal('architect', 4, 1)->default(1.0);
            $table->decimal('publish', 4, 1)->default(1.0);
            $table->decimal('question', 4, 1)->default(10.0);
            $table->decimal('study', 4, 1)->default(1.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
