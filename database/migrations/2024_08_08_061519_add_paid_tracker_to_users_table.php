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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('isMage')->after('showTutorial')->default(0);
            $table->string('gift_reason')->after('isMage')->nullable();
            $table->date('mage_expires_on')->after('gift_reason')->nullable();
            $table->date('subscribed_on')->after('mage_expires_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['isMage', 'gift_reason', 'mage_expires_on', 'subscribed_on']);
        });
    }
};
