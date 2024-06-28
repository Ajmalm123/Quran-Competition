<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id', 10);
            $table->string('full_name');
            $table->enum('gender', ['Male', 'Female']);
            $table->date('date_of_birth');
            $table->enum('mother_tongue', ['Malayalam', 'Other']);
            $table->enum('educational_qualification', ['SSLC', 'Plus Two', 'Degree', 'Above Degree']);
            $table->string('aadhar_number', 12);
            $table->string('job', 100)->nullable();
            $table->string('contact_number', 15);
            $table->string('whatsapp', 15)->nullable();
            $table->string('email');
            $table->text('c_address');
            $table->text('pr_address');
            $table->enum('district', [
                'Kasaragod', 'Kannur', 'Wayanad', 'Kozhikode', 'Malappuram', 'Palakkad', 'Thrissur', 'Ernakulam',
                'Idukki', 'Kottayam', 'Alappuzha', 'Pathanamthitta', 'Kollam', 'Thiruvananthapuram'
            ]);
            $table->string('pincode',10);
            $table->text('institution_name')->nullable();
            $table->enum('is_completed_ijazah', ['Yes', 'No']);
            $table->text('qirath_with_ijazah')->nullable();
            $table->enum('primary_competition_participation', ['Kerala', 'native']);
            $table->enum('zone', ['Kollam', 'Ernakulam', 'Malappuram', 'Kannur', 'Jeddah', 'Dubai', 'Doha', 'Bahrain', 'Muscat', 'Kuwait']);
            $table->string('passport_size_photo')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->string('letter_of_recommendation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
