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
            $table->enum('type',['Физлицо','Юрлицо'])->default('Юрлицо');
            $table->string('name',100);
            $table->string('fio',100)->nullable();
            $table->integer('group_id')->unsigned()->nullable();
            $table->string('inn',12)->nullable();
            $table->string('kpp',9)->nullable();
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
