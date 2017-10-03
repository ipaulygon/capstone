<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarrantyJobPromoProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranty_job_promo_product', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('warrantyId');
            $table->unsignedInteger('jobPromoId');
            $table->unsignedInteger('productId');
            $table->integer('quantity');
            $table->timestamps();
            $table->foreign('warrantyId')
                  ->references('id')->on('warranty_job_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('jobPromoId')
                  ->references('id')->on('job_promo')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('productId')
                  ->references('id')->on('product')
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
        Schema::dropIfExists('warranty_job_promo_product');
    }
}
