<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_discount', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('salesId');
            $table->unsignedInteger('discountId');
            $table->boolean('isActive')->default(1);
            $table->timestamps();
            $table->foreign('salesId')
                  ->references('id')->on('sales_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('discountId')
                  ->references('id')->on('discount')
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
        Schema::dropIfExists('sales_discount');
    }
}
