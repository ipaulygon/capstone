<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_service', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('discountId');
            $table->unsignedInteger('serviceId');
            $table->foreign('discountId')
                  ->references('id')->on('discount')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('serviceId')
                  ->references('id')->on('service')
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
        Schema::dropIfExists('discount_service');
    }
}
