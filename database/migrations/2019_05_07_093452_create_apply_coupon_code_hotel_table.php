<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplyCouponCodeHotelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('apply_coupon_code_hotel', function (Blueprint $table) {
        //     $table->integer('coupon_code_id')->unsigned();
        //     $table->integer('hotel_id')->unsigned();
        //     $table->timestamps();
        //     $table->foreign('coupon_code_id')
        //     ->references('id')->on('coupon_code')
        //     ->onDelete('cascade');
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
        // Schema::dropIfExists('apply_coupon_code_hotel');
    }
}
