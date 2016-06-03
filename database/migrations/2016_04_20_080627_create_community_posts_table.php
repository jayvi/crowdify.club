<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunityPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('community_id')->unsigned();
            $table->integer('owner_id')->unsigned();
            $table->boolean('status')->default(false);
            $table->boolean('pinned')->default(false);
            $table->string('subject')->nullable();
            $table->longText('post');
            $table->timestamps();

            $table->foreign('community_id')->references('id')->on('communities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('community_posts');
    }
}
