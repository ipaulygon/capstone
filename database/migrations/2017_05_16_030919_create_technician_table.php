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
            $table->string('firstName', 45);
            $table->string('middleName', 45);
            $table->string('lastName', 45);
            $table->text('street');
            $table->text('brgy');
            $table->text('city');
            $table->date('birthdate');
            $table->string('contact', 30);
            $table->string('email', 45)->nullable();
            $table->text('image')->nullable();
            $table->unique(['firstName', 'middleName','lastName']);
            $table->boolean('isActive')->default(1);
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
