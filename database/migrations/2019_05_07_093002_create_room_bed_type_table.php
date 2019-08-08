<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomBedTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_bed_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bed_type_id')->unsigned();
            $table->integer('room_id')->unsigned();
            $table->integer('amount');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('bed_type_id')
            ->references('id')->on('bed_type')
            ->onDelete('cascade');
            $table->foreign('room_id')
            ->references('id')->on('room')
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
        Schema::dropIfExists('room_bed_type');
    }
}
