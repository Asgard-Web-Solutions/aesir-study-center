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
        Schema::table('exam_records', function (Blueprint $table) {
            $table->dateTime('available_at')->nullable()->change();
            $table->dateTime('last_completed')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_records', function (Blueprint $table) {
            $table->date('available_at')->nullable()->change();
            $table->date('last_completed')->nullable()->change();
        });
    }
};
