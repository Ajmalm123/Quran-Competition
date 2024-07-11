<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('zone_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->string('center_id');
            $table->date('date');
            $table->time('time');
            $table->timestamps();
            
            $table->unique(['zone_id', 'center_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('zone_assignments');
    }
};