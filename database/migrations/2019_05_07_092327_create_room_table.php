<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room', function (Blueprint $table) {
            $table->increments('id');
            $table->string('room_name');
            $table->text('description');
            $table->double('price');
            $table->integer('max_adult_amount');
            $table->integer('max_child_amount')->default(0);
            $table->integer('free_child_amount')->default(0);
            $table->double('room_size');
            $table->integer('amount');
            $table->integer('room_mode_id')->unsigned();
            $table->integer('room_type_id')->unsigned();
            $table->integer('hotel_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('room_mode_id')
            ->references('id')->on('room_mode')
            ->onDelete('cascade');
            $table->foreign('room_type_id')
            ->references('id')->on('room_type')
            ->onDelete('cascade');
            $table->foreign('hotel_id')
            ->references('id')->on('hotel')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room');
    }
}
