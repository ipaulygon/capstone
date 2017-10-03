<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesPromoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_promo', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('salesId');
            $table->unsignedInteger('promoId');
            $table->integer('quantity');
            $table->boolean('isActive')->default(1);
            $table->foreign('salesId')
                  ->references('id')->on('sales_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('promoId')
                  ->references('id')->on('promo')
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
        Schema::dropIfExists('sales_promo');
    }
}
