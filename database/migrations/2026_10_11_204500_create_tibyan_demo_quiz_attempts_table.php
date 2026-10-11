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
        Schema::create('tibyan_demo_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id', 100)->nullable()->index();
            $table->string('session_id', 100)->nullable()->index();
            $table->char('selected_option', 2)->index(); // A, B, C, D
            $table->boolean('is_correct')->default(false)->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tibyan_demo_quiz_attempts');
    }
};
