<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_header', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('customerId');
            $table->unsignedInteger('vehicleId');
            $table->unsignedInteger('rackId');
            $table->text('remarks');
            $table->timestamps();
            $table->foreign('customerId')
                  ->references('id')->on('customer')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('vehicleId')
                  ->references('id')->on('vehicle')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('rackId')
                  ->references('id')->on('rack')
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
        Schema::dropIfExists('inspection_header');
    }
}
