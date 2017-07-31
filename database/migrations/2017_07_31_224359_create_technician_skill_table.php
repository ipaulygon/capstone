<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicianSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technician_skill', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('technicianId');
            $table->unsignedInteger('categoryId');
            $table->foreign('technicianId')
                  ->references('id')->on('technician')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('categoryId')
                  ->references('id')->on('service_category')
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
        Schema::dropIfExists('technician_skill');
    }
}
