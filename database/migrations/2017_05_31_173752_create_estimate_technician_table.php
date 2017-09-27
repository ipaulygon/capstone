<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimateTechnicianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_technician', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('estimateId');
            $table->unsignedInteger('technicianId');
            $table->boolean('isActive')->default(1);
            $table->timestamps();
            $table->foreign('estimateId')
                  ->references('id')->on('estimate_header')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('technicianId')
                  ->references('id')->on('technician')
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
        Schema::dropIfExists('estimate_technician');
    }
}
