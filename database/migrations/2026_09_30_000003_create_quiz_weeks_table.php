<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_weeks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('week_number')->unique();
            $table->date('quiz_date');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('status', 20)->default('draft')->index(); // draft, upcoming, live, completed, cancelled
            $table->timestamps();

            $table->index(['status', 'start_at', 'end_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_weeks');
    }
};
