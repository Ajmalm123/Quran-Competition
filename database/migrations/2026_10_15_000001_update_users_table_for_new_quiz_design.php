<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'dob')) {
                $table->date('dob')->nullable()->after('age');
            }
            if (!Schema::hasColumn('users', 'place')) {
                $table->string('place')->nullable()->after('locality');
            }

            // Ensure all optional profile fields are nullable with safe defaults
            $table->string('country_code', 10)->nullable()->default('+91')->change();
            $table->string('locality')->nullable()->change();
            $table->string('territory')->nullable()->change();
            $table->unsignedSmallInteger('age')->nullable()->change();
            $table->string('whatsapp_number', 25)->nullable()->change();
            $table->boolean('whatsapp_verified')->default(true)->change();
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'dob')) {
                $table->dropColumn('dob');
            }
            if (Schema::hasColumn('users', 'place')) {
                $table->dropColumn('place');
            }
        });
    }
};
