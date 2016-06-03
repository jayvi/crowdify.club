<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBidTalentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bidtalent', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bidder_id')->unsigned();
            $table->string('msg');
            $table->integer('status');
            $table->timestamps();
            $table->foreign('bidder_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
