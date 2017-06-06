<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('firstName', 100);
            $table->string('middleName', 100);
            $table->string('lastName', 100);
            $table->text('street')->nullable();
            $table->text('brgy')->nullable();
            $table->text('city');
            $table->string('contact', 30);
            $table->string('email')->nullable();
            $table->unique(['firstName', 'middleName','lastName']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
