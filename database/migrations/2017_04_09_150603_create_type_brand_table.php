<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_brand', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('typeId');
            $table->unsignedInteger('brandId');
            $table->foreign('typeId')
                  ->references('id')->on('product_type')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('brandId')
                  ->references('id')->on('product_brand')
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
        Schema::dropIfExists('type_brand');
    }
}
