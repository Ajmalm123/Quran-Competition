<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE applications MODIFY admit_status ENUM('Pending', 'Admitted', 'Declined', 'Absent', 'Completed', 'Move to Final') DEFAULT 'Pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE applications MODIFY admit_status ENUM('Pending', 'Admitted', 'Declined', 'Absent', 'Completed') DEFAULT 'Pending'");
    }
};
