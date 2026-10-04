<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lucky_draws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_week_id')->unique()->constrained('quiz_weeks')->cascadeOnDelete();
            $table->foreignId('winner_user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('status', 20)->default('pending')->index(); // pending, drawn, confirmed, published
            $table->dateTime('drawn_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['quiz_week_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lucky_draws');
    }
};
