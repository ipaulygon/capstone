<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionTechnicianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_technician', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('inspectionId');
            $table->unsignedInteger('technicianId');
            $table->boolean('isActive');
            $table->timestamps();
            $table->foreign('inspectionId')
                  ->references('id')->on('inspection_header')
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
        Schema::dropIfExists('inspection_technician');
    }
}
