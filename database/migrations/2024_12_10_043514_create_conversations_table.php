<?php

use App\Models\Insight;
use App\Models\User;
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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Insight::class);
            $table->foreignIdFor(User::class);
            $table->string('title')->nullable();
            $table->dateTime('last_message_date')->nullable();
            $table->dateTime('isLocked')->nullable();
            $table->dateTime('isPublic')->nullable();
            $table->text('summary')->nullable();
            $table->dateTime('summary_at')->nullable();
            $table->integer('message_since_summary')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
