<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('review_point', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('point');
        //     $table->integer('customer_id')->unsigned();
        //     $table->integer('review_id')->unsigned();
        //     $table->timestamps();
        //     $table->foreign('customer_id')
        //     ->references('user_id')->on('customer')
        //     ->onDelete('cascade');
        //     $table->foreign('review_id')
        //     ->references('id')->on('review')
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
        // Schema::dropIfExists('review_point');
    }
}
