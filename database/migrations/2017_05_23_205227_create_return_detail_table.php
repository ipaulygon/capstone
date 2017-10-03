<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('returnId', 50);
            $table->unsignedInteger('productId');
            $table->string('deliveryId', 50);
            $table->integer('quantity');
            $table->boolean('isActive')->default(1);
            $table->timestamps();
            $table->foreign('returnId')
                  ->references('id')->on('return_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('productId')
                  ->references('id')->on('product')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('deliveryId')
                  ->references('id')->on('delivery_header')
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
        Schema::dropIfExists('return_detail');
    }
}
