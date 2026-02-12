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
        // Drop the lessons JSON column from sets table
        Schema::table('sets', function (Blueprint $table) {
            $table->dropColumn('lessons');
        });

        // Change lesson columns from string to foreign key
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('lesson');
            $table->unsignedBigInteger('lesson_id')->nullable()->after('group_id');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('set null');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('lesson');
            $table->unsignedBigInteger('lesson_id')->nullable()->after('question');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('set null');
        });

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropColumn('lesson');
            $table->unsignedBigInteger('lesson_id')->nullable()->after('question_count');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropColumn('lesson_id');
            $table->string('lesson')->nullable();
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropColumn('lesson_id');
            $table->string('lesson')->nullable();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropColumn('lesson_id');
            $table->string('lesson')->nullable();
        });

        Schema::table('sets', function (Blueprint $table) {
            $table->json('lessons')->nullable();
        });
    }
};
