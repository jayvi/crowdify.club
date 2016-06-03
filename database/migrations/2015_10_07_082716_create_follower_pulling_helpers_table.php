<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowerPullingHelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follower_pulling_helpers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profile_id')->unsigned();
            $table->string('cursor')->nullable();
            $table->boolean('is_limit_exceeded')->default(false);
            $table->dateTime('next_request_time')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->integer('number_of_request')->default(0);

            $table->timestamps();

            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('follower_pulling_helpers');
    }
}
