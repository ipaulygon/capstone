<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('purchaseId', 50);
            $table->unsignedInteger('productId');
            $table->unsignedInteger('modelId')->nullable();
            $table->boolean('isManual')->nullable();
            $table->integer('quantity');
            $table->integer('delivered');
            $table->double('price', 15,2);
            $table->timestamps();
            $table->boolean('isActive')->default(1);
            $table->foreign('purchaseId')
                  ->references('id')->on('purchase_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('productId')
                  ->references('id')->on('product')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('modelId')
                  ->references('id')->on('vehicle_model')
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
        Schema::dropIfExists('purchase_detail');
    }
}
