<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarrantyJobHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranty_job_header', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('jobId');
            $table->unsignedInteger('warrantyJobId');
            $table->timestamps();
            $table->foreign('jobId')
                  ->references('id')->on('job_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('warrantyJobId')
                  ->references('id')->on('job_header')
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
        Schema::dropIfExists('warranty_job_header');
    }
}
