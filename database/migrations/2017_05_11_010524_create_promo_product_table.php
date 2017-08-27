<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_product', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('promoId');
            $table->unsignedInteger('productId');
            $table->integer('quantity')->default(0);
            $table->integer('freeQuantity')->default(0);
            $table->boolean('isActive')->default(1);
            $table->foreign('promoId')
                  ->references('id')->on('promo')
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
        Schema::dropIfExists('promo_product');
    }
}
