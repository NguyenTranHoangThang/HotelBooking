<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplyCouponCodeRoomTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apply_coupon_code_room_type', function (Blueprint $table) {
            $table->integer('coupon_code_id')->unsigned();
            $table->integer('room_type_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('coupon_code_id')
            ->references('id')->on('coupon_code')
            ->onDelete('cascade');
            $table->foreign('room_type_id')
            ->references('id')->on('room_type')
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
        Schema::dropIfExists('apply_coupon_code_room_type');
    }
}
