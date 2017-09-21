<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 50);//for coding purposes
            $table->text('description', 50);
            $table->double('price', 15,2);
            $table->integer('reorder');
            $table->unsignedInteger('typeId');
            $table->unsignedInteger('brandId');
            $table->unsignedInteger('varianceId');
            $table->string('isOriginal',50)->nullable();
            $table->boolean('isWarranty')->default(1);
            $table->integer('year')->default(1);
            $table->integer('month')->default(0);
            $table->integer('day')->default(0);
            $table->boolean('isActive')->default(1);
            $table->foreign('typeId')
                  ->references('id')->on('product_type')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('brandId')
                  ->references('id')->on('product_brand')
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
        Schema::dropIfExists('product');
    }
}
