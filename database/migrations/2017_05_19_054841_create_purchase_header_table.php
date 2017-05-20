<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_header', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id', 50)->primary();
            $table->unsignedInteger('supplierId');
            $table->boolean('isActive');
            $table->timestamps();
            $table->foreign('supplierId')
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
        Schema::dropIfExists('purchase_header');
    }
}
