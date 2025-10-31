<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddMangaluruToDistrictInApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modify the district column to add 'Mangaluru' option
        DB::statement("ALTER TABLE `applications` MODIFY `district` ENUM(
            'Kasaragod',
            'Kannur',
            'Wayanad',
            'Kozhikode',
            'Malappuram',
            'Palakkad',
            'Thrissur',
            'Ernakulam',
            'Idukki',
            'Kottayam',
            'Alappuzha',
            'Pathanamthitta',
            'Kollam',
            'Thiruvananthapuram',
            'Mangaluru'
        )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the district column to its original state
        DB::statement("ALTER TABLE `applications` MODIFY `district` ENUM(
            'Kasaragod',
            'Kannur',
            'Wayanad',
            'Kozhikode',
            'Malappuram',
            'Palakkad',
            'Thrissur',
            'Ernakulam',
            'Idukki',
            'Kottayam',
            'Alappuzha',
            'Pathanamthitta',
            'Kollam',
            'Thiruvananthapuram'
        )");
    }
}
