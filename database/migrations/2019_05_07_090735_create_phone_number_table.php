<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhoneNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('phone_number', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('phone_number');
        //     $table->integer('hotel_id')->unsigned();
        //     $table->timestamps();
        //     $table->foreign('hotel_id')
        //     ->references('id')->on('hotel')
        //     ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('phone_number');
    }
}
