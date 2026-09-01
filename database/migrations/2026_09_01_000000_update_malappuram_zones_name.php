<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('zones')->where('name', 'Malappuram 1')->update(['name' => 'Malappuram N']);
        DB::table('zones')->where('name', 'Malappuram 2')->update(['name' => 'Malappuram S']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('zones')->where('name', 'Malappuram N')->update(['name' => 'Malappuram 1']);
        DB::table('zones')->where('name', 'Malappuram S')->update(['name' => 'Malappuram 2']);
    }
};
