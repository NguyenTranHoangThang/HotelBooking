<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('verified')->default(0);
            $table->string('credit_card');
            $table->string('email');
            $table->string('address');
            $table->text('description');
            $table->integer('rank_point')->default(0);
            $table->integer('stars_num')->default(0);
            $table->string('phone_number');
            $table->string('name');
            $table->string('meta_name');
            $table->integer('child_age')->default(13);
            $table->string('tax_code');
            $table->double('review_point')->default(0);
            $table->string('bank');
            $table->integer('coin')->default(0);
            $table->integer('ward_id')->unsigned();
            $table->integer('hotel_type_id')->unsigned();
            $table->integer('hotel_manager_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('hotel_type_id')
            ->references('id')->on('hotel_type')
            ->onDelete('cascade');
            $table->foreign('hotel_manager_id')
            ->references('user_id')->on('hotel_manager')
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
        Schema::dropIfExists('hotel');
    }
}
