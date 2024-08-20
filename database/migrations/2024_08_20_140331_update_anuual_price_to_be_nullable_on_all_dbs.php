<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->float('annual_price')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Revert back to original type or state as needed
            $table->decimal('annual_price', 6, 2)->nullable()->change();
        });
    }
};
