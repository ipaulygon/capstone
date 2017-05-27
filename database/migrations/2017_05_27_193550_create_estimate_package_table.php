<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimatePackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_package', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('estimateId');
            $table->unsignedInteger('packageId');
            $table->integer('quantity');
            $table->boolean('isActive');
            $table->foreign('estimateId')
                  ->references('id')->on('estimate_header')
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
        Schema::dropIfExists('estimate_package');
    }
}
