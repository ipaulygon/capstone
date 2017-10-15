<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarrantySalesPromoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranty_sales_promo', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('warrantyId');
            $table->unsignedInteger('salesPromoId');
            $table->unsignedInteger('productId');
            $table->integer('quantity');
            $table->timestamps();
            $table->foreign('warrantyId')
                  ->references('id')->on('warranty_sales_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('salesPromoId')
                  ->references('id')->on('sales_promo')
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
        Schema::dropIfExists('warranty_sales_promo');
    }
}
