<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUtilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utilities', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('image')->nullable();
            $table->string('name',20)->default('iRepair');
            $table->text('address');
            $table->string('category1',50)->default('Parts');
            $table->string('category2',50)->default('Supplies');
            $table->string('type1',50)->default('Original');
            $table->string('type2',50)->default('Replacement');
            $table->integer('max')->default(100);
            $table->integer('backlog')->default(7);
            $table->boolean('isVat')->default(1);
            $table->integer('vat')->default(12);
            $table->boolean('isWarranty')->default(1);
            $table->integer('year')->default(1);
            $table->integer('month')->default(0);
            $table->integer('day')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utilities');
    }
}
