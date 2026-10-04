<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();

            if (!Schema::hasColumn('users', 'age')) {
                $table->unsignedSmallInteger('age')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'locality')) {
                $table->string('locality')->nullable()->after('age')->index();
            }
            if (!Schema::hasColumn('users', 'whatsapp_number')) {
                $table->string('whatsapp_number', 25)->nullable()->unique()->after('locality');
            }
            if (!Schema::hasColumn('users', 'whatsapp_verified')) {
                $table->boolean('whatsapp_verified')->default(false)->after('whatsapp_number')->index();
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status', 20)->default('active')->after('whatsapp_verified')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['age', 'locality', 'whatsapp_number', 'whatsapp_verified', 'status'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
