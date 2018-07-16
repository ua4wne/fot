<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bik',10)->unique();
            $table->string('swift',15)->unique();
            $table->string('name',70)->unique();
            $table->string('account',30)->unique();
            $table->string('city',50)->nullable();
            $table->string('address',100)->nullable();
            $table->string('phone',70)->nullable();
            $table->string('country',50)->nullable();
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
        Schema::dropIfExists('banks');
    }
}
