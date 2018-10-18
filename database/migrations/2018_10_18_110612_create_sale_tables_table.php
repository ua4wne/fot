<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_id')->unsigned();
            $table->foreign('sale_id')->references('id')->on('sales');
            $table->integer('nomenclature_id')->unsigned();
            $table->foreign('nomenclature_id')->references('id')->on('nomenclatures');
            $table->integer('qty');
            $table->decimal('price',11,2);
            $table->decimal('amount',11,2);
            $table->integer('buhcode_id')->unsigned();
            $table->foreign('buhcode_id')->references('id')->on('buhcodes');
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
        Schema::dropIfExists('sale_tables');
    }
}
