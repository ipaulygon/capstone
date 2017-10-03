<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarrantyJobPromoServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranty_job_promo_service', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('warrantyId');
            $table->unsignedInteger('jobPromoId');
            $table->unsignedInteger('serviceId');
            $table->timestamps();
            $table->foreign('warrantyId')
                  ->references('id')->on('warranty_job_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('jobPromoId')
                  ->references('id')->on('job_promo')
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
        Schema::dropIfExists('warranty_job_promo_service');
    }
}
