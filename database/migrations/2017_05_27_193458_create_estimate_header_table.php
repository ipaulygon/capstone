<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimateHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_header', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('customerId');
            $table->unsignedInteger('vehicleId');
            $table->boolean('isFinalize');
            $table->timestamps();
            $table->foreign('customerId')
                  ->references('id')->on('customer')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('vehicleId')
                  ->references('id')->on('vehicle')
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
        Schema::dropIfExists('estimate_header');
    }
}