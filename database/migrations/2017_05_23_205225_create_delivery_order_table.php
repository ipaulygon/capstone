<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_order', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('deliveryId', 50);
            $table->string('purchaseId', 50);
            $table->timestamps();
            $table->foreign('deliveryId')
                  ->references('id')->on('delivery_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('purchaseId')
                  ->references('id')->on('purchase_header')
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
        Schema::dropIfExists('delivery_order');
    }
}
