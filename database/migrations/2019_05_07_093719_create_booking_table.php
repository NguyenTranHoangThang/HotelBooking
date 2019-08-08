<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hotel_name');
            $table->integer('room_price');
            $table->integer('room_amount');
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('contact_address');
            $table->string('special_request')->nullable();
            $table->string('bank');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('room_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->integer('payment_method_id')->unsigned();
            $table->string('coupon_code')->nullable();
            $table->integer('discount_value')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('room_id')
            ->references('id')->on('room')
            ->onDelete('cascade');
            $table->foreign('customer_id')
            ->references('user_id')->on('customer')
            ->onDelete('cascade');
            $table->foreign('status_id')
            ->references('id')->on('booking_status')
            ->onDelete('cascade');
            $table->foreign('payment_method_id')
            ->references('id')->on('payment_method')
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
        Schema::dropIfExists('booking');
    }
}
