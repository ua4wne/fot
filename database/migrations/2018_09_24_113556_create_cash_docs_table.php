<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_docs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('doc_num',15);
            $table->integer('operation_id')->unsigned();
            $table->foreign('operation_id')->references('id')->on('operations');
            $table->integer('buhcode_id')->unsigned();
            $table->foreign('buhcode_id')->references('id')->on('buhcodes');
            $table->integer('org_id')->unsigned();
            $table->foreign('org_id')->references('id')->on('orgs');
            $table->integer('firm_id')->unsigned();
            $table->foreign('firm_id')->references('id')->on('firms');
            $table->decimal('amount');
            $table->string('contract',150)->nullable();
            $table->string('comment',200)->nullable();
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
        Schema::dropIfExists('cash_docs');
    }
}
