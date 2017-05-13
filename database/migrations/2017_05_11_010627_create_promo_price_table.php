<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_price', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('promoId');
            $table->double('price', 15,2);
            $table->timestamps();
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
        Schema::dropIfExists('promo_price');
    }
}
