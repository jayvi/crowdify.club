<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunityPostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_post_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('community_post_id')->unsigned();
            $table->integer('commenter_id')->unsigned();
            $table->text('comment');
            $table->timestamps();

            $table->foreign('community_post_id')->references('id')->on('community_posts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('commenter_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('community_post_comments');
    }
}
