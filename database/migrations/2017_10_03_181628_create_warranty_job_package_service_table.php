<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarrantyJobPackageServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranty_job_package_service', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('warrantyId');
            $table->unsignedInteger('jobPackageId');
            $table->unsignedInteger('serviceId');
            $table->timestamps();
            $table->foreign('warrantyId')
                  ->references('id')->on('warranty_job_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('jobPackageId')
                  ->references('id')->on('job_package')
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
        Schema::dropIfExists('warranty_job_package_service');
    }
}
