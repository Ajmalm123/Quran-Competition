<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_weeks', function (Blueprint $table) {
            $table->string('winner_announcement_day', 255)
                ->default('Saturday · 7:00 PM')
                ->after('end_at');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_weeks', function (Blueprint $table) {
            $table->dropColumn('winner_announcement_day');
        });
    }
};
