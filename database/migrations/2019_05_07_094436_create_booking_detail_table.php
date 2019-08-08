<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('booking_detail', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('room_type');
        //     $table->integer('price');
        //     $table->integer('adult_amount');
        //     $table->integer('child_amount');
        //     $table->string('bed_type');
        //     $table->timestamp('check_in')->nullable();
        //     $table->timestamp('check_out')->nullable();
        //     $table->integer('booking_id')->unsigned();
        //     $table->integer('room_id')->unsigned();
        //     $table->timestamps();
        //     $table->foreign('booking_id')
        //     ->references('id')->on('booking')
        //     ->onDelete('cascade');
        //     $table->foreign('room_id')
        //     ->references('id')->on('room')
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
        // Schema::dropIfExists('booking_detail');
    }
}
