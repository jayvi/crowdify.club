<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweet_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('crowdify_profile_id')->unsigned();
            $table->string('twitter_id')->unique();
            $table->string('name')->nullable();
            $table->string('screen_name');
            $table->string('location_name')->nullable();
            $table->string('description')->nullable();
            $table->string('url')->nullable();
            $table->longText('entities')->nullable();
            $table->integer('followers_count')->default(0);
            $table->integer('friends_count')->default(0);
            $table->integer('listed_count')->default(0);
            $table->dateTime('user_created_at')->nullable();
            $table->integer('favourites_count')->default(0);
            $table->integer('utc_offset')->default(0);
            $table->string('time_zone')->nullable();
            $table->integer('statuses_count')->default(0);
            $table->string('lang')->nullable();
            $table->boolean('contributor_enabled')->default(false);
            $table->boolean('is_translator')->default(false);
            $table->boolean('is_translation_enabled')->default(false);
            $table->boolean('has_extended_profile')->default(false);
            $table->string('default_profile')->nullable();
            $table->string('following')->nullable();
            $table->integer('follow_request_sent')->default(0);
            $table->timestamps();

            $table->foreign('crowdify_profile_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tweet_users');
    }
}
