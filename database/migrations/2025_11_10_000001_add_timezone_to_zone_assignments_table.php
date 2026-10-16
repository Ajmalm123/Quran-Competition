<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zone_assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('zone_assignments', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('zone_assignments', 'timezone')) {
                $table->string('timezone', 64)->default('IST');
            }
        });
    }

    public function down(): void
    {
        Schema::table('zone_assignments', function (Blueprint $table) {
            $table->dropColumn('timezone');
        });
    }
};

