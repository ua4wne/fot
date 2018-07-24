<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firms', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type',['physical','legal_entity'])->default('legal_entity');
            $table->string('name',90);
            $table->string('full_name',180)->nullable();
            $table->integer('group_id')->unsigned()->nullable();
            $table->string('inn',12)->nullable();
            $table->string('kpp',9)->nullable();
            $table->integer('acc_id')->unsigneg()->nullable();
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
        Schema::dropIfExists('firms');
    }
}
