<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('inspectionId');
            $table->unsignedInteger('itemId');
            $table->text('remarks');
            $table->boolean('isActive')->default(1);
            $table->timestamps();
            $table->foreign('inspectionId')
                  ->references('id')->on('inspection_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('itemId')
                  ->references('id')->on('inspection_item')
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
        Schema::dropIfExists('inspection_detail');
    }
}
