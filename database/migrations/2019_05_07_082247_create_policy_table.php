<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePolicyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_id')->unsigned();
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->integer('cancelable')->default(0);
            $table->integer('can_refund')->default(0)->max(100);
            $table->text('content')->nullable();
            $table->tinyInteger('payment_method')->default(2);//0:off,1:on,2:both
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('policy');
    }
}
