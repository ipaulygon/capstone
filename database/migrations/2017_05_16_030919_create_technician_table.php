<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technician', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('firstName', 100);
            $table->string('middleName', 100);
            $table->string('lastName', 100);
            $table->text('street');
            $table->text('brgy');
            $table->text('city');
            $table->date('birthdate');
            $table->string('contact', 30);
            $table->string('email')->nullable();
            $table->text('image')->nullable();
            $table->unique(['firstName', 'middleName','lastName']);
            $table->boolean('isActive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('technician');
    }
}
