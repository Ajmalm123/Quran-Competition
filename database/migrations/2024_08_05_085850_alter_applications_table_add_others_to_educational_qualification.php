<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterApplicationsTableAddOthersToEducationalQualification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modify the educational_qualification column to add 'Others' option
        DB::statement("ALTER TABLE `applications` MODIFY `educational_qualification` ENUM('SSLC', 'Plus Two', 'Degree', 'Above Degree', 'Other') DEFAULT 'SSLC'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the educational_qualification column to its original state
        DB::statement("ALTER TABLE `applications` MODIFY `educational_qualification` ENUM('SSLC', 'Plus Two', 'Degree', 'Above Degree') DEFAULT 'SSLC'");
    }
}
