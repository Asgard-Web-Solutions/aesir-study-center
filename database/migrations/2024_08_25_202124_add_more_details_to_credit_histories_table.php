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
        Schema::table('credit_histories', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->change();
            $table->foreignId('set_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_histories', function (Blueprint $table) {
            $table->dropColumn('set_id');
            $table->foreignId('product_id')->nullable(false)->change();
        });
    }
};
