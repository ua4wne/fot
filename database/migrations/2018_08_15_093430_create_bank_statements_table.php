<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('operation_id')->unsigned();
            $table->string('regnum',15)->unique();
            $table->integer('firm_id')->unsigned();
            $table->decimal('amount');
            $table->integer('contract_id')->unsigned();
            $table->string('acc_num',5);
            $table->string('calc_num',5);
            $table->string('av_num',5);
            $table->integer('org_id')->unsigned();
            $table->string('purpose',255);
            $table->text('text')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('operation_id')->references('id')->on('operations');
            $table->foreign('firm_id')->references('id')->on('firms');
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->foreign('org_id')->references('id')->on('orgs');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_statements');
    }
}
