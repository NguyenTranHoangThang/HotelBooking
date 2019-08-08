<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('hotel_address', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('ward_id')->unsigned();
        //     $table->integer('hotel_id')->unsigned();
        //     $table->string('address');
        //     $table->timestamps();
        //     $table->foreign('ward_id')
        //         ->references('id')->on('ward')
        //         ->onDelete('cascade');
        //     $table->foreign('hotel_id')
        //         ->references('id')->on('hotel')
        //         ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('hotel_address');
    }
}
