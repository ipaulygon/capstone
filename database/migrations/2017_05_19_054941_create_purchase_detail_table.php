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
            $table->string('purchaseId', 50);
            $table->unsignedInteger('productId');
            $table->integer('quantity');
            $table->integer('delivered');
            $table->timestamps();
            $table->foreign('purchaseId')
                  ->references('id')->on('purchase_header')
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
        Schema::dropIfExists('purchase_detail');
    }
}
