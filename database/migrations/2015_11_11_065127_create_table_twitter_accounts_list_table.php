<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTwitterAccountsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_accounts_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->boolean('pull_followers')->default(false);
            $table->boolean('pull_friends')->default(false);
            $table->boolean('is_followers_pulled')->default(false);
            $table->boolean('is_friends_pulled')->default(false);
            $table->boolean('is_private')->default(false);
            $table->string('cursor_followers')->default('-1');
            $table->string('cursor_friends')->default('-1');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('twitter_accounts_list');
    }
}
