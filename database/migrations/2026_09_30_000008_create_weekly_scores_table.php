<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('quiz_week_id')->constrained('quiz_weeks')->cascadeOnDelete();
            $table->unsignedTinyInteger('score')->default(0)->index();
            $table->timestamps();

            $table->unique(['user_id', 'quiz_week_id']);
            $table->index(['user_id', 'score']);
            $table->index(['quiz_week_id', 'score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_scores');
    }
};
