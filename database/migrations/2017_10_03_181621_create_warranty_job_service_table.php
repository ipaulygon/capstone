<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarrantyJobServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranty_job_service', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('warrantyId');
            $table->unsignedInteger('jobServiceId');
            $table->unsignedInteger('serviceId');
            $table->timestamps();
            $table->foreign('warrantyId')
                  ->references('id')->on('warranty_job_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('jobServiceId')
                  ->references('id')->on('job_service')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('serviceId')
                  ->references('id')->on('service')
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
        Schema::dropIfExists('warranty_job_service');
    }
}
