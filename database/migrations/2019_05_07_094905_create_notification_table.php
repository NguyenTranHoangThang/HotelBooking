<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('notification', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('customer_id')->unsigned();
        //     $table->string('content');
        //     $table->string('link');
        //     $table->timestamps();
        //     $table->foreign('customer_id')
        //     ->references('user_id')->on('customer')
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
        // Schema::dropIfExists('notification');
    }
}
