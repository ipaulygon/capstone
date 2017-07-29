<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_person', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('spId');
            $table->string('spName', 100);
            $table->string('spContact', 30);
            $table->boolean('isMain');
            $table->foreign('spId')
                  ->references('id')->on('supplier')
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
        Schema::dropIfExists('supplier_person');
    }
}
