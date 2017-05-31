<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_rate', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('discountId');
            $table->double('rate', 5,2);
            $table->timestamps();
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
        Schema::dropIfExists('discount_rate');
    }
}
