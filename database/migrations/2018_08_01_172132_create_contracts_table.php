<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('num_doc',15)->unique();
            $table->string('name',100);
            $table->integer('tdoc_id')->unsigned();
            $table->integer('org_id')->unsigned();
            $table->integer('firm_id')->unsigned();
            $table->text('text')->nullable();
            $table->date('start');
            $table->date('stop')->nullable();
            $table->integer('currency_id')->unsigned();
            $table->integer('settlement_id')->unsigned();
            $table->timestamps();
            $table->foreign('tdoc_id')->references('id')->on('typedocs');
            $table->foreign('org_id')->references('id')->on('orgs');
            $table->foreign('firm_id')->references('id')->on('firms');
            $table->foreign('settlement_id')->references('id')->on('settlements');
            $table->foreign('currency_id')->references('id')->on('currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
