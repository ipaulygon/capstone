<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeVarianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_variance', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('typeId');
            $table->unsignedInteger('varianceId');
            $table->foreign('typeId')
                  ->references('id')->on('product_type')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('varianceId')
                  ->references('id')->on('product_variance')
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
        Schema::dropIfExists('type_variance');
    }
}
