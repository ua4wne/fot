<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvanceTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('advance_id')->unsigned();
            $table->foreign('advance_id')->references('id')->on('advances');
            $table->string('text',100);
            $table->integer('firm_id')->unsigned();
            $table->foreign('firm_id')->references('id')->on('firms');
            $table->integer('contract_id')->unsigned();
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->string('comment',200)->nullable();
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
        Schema::dropIfExists('advance_tables');
    }
}
