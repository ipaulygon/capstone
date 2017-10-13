<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_package', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('salesId');
            $table->unsignedInteger('packageId');
            $table->integer('quantity');
            $table->boolean('isActive')->default(1);
            $table->timestamps();
            $table->foreign('salesId')
                  ->references('id')->on('sales_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('packageId')
                  ->references('id')->on('package')
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
        Schema::dropIfExists('sales_package');
    }
}
