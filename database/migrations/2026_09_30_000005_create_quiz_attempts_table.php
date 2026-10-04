<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('quiz_week_id')->constrained('quiz_weeks')->cascadeOnDelete();
            $table->dateTime('started_at');
            $table->dateTime('submitted_at')->nullable();
            $table->string('status', 20)->default('in_progress')->index(); // in_progress, completed, abandoned
            $table->unsignedTinyInteger('weekly_score')->default(0)->index();
            $table->timestamps();

            // Enforce requirement: One attempt per week per participant
            $table->unique(['user_id', 'quiz_week_id']);
            $table->index(['quiz_week_id', 'status', 'weekly_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
