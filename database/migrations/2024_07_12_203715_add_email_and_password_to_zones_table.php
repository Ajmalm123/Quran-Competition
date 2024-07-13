<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailAndPasswordToZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zones', function (Blueprint $table) {
            $table->string('email')->unique()->after('name');
            $table->string('password')->after('email');
            $table->enum('area', ['Native', 'Abroad'])->after('password');
            $table->timestamp('email_verified_at')->nullable()->after('area');
            $table->string('avatar')->nullable()->after('email_verified_at');
            $table->string('phone_number')->nullable()->after('avatar');
            $table->string('status')->nullable()->after('phone_number');
            $table->rememberToken()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zones', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('password');
            $table->dropColumn('area');
            $table->dropColumn('email_verified_at');
            $table->dropColumn('avatar');
            $table->dropColumn('phone_number');
            $table->dropColumn('status');
            $table->dropRememberToken();
        });
    }
}
