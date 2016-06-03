<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tweet_user_id')->unsigned();
            $table->string('tweet_id')->unique();
            $table->dateTime('tweeted_at');
            $table->string('text');
            $table->longText('entities')->nullable();
            $table->string('source')->nullable();
            $table->string('in_reply_to_status_id')->nullable();
            $table->string('in_reply_to_user_id')->nullable();
            $table->string('geo')->nullable();
            $table->string('coordinates')->nullable();
            $table->string('place')->nullable();
            $table->string('contributors')->nullable();
            $table->boolean('is_quote_status')->default(false);
            $table->integer('retweet_count')->default(0);
            $table->integer('favourite_count')->default(0);
            $table->boolean('favourited')->default(false);
            $table->boolean('retweeted')->default(false);
            $table->boolean('possibly_sensitive')->default(false);
            $table->string('lang')->nullable();

            $table->timestamps();

            $table->foreign('tweet_user_id')->references('id')->on('tweet_users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tweets');
    }
}
