<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTechnicianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_technician', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('jobId');
            $table->unsignedInteger('technicianId');
            $table->boolean('isActive')->default(1);
            $table->timestamps();
            $table->foreign('jobId')
                  ->references('id')->on('job_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('technicianId')
                  ->references('id')->on('technician')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_technician');
    }
}
