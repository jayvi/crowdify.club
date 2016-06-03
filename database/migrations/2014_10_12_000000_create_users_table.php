<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('usertype_id')->unsigned();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email',100)->unique();
            $table->string('username')->unique();
            $table->string('password', 60)->nullable();
            $table->string('timezone',60)->nullable();
            $table->string('avatar')->nullable();
            $table->string('avatar_original')->nullable();
            $table->boolean('verified')->default(false);
            $table->boolean('is_email')->default(false);
            $table->integer('step_completed')->default(1);
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->enum('gender',['Male','Female'])->default(null);
            $table->date('birth_date')->nullable();
            $table->string('website')->nullable();
            $table->string('bio',5000)->nullable();
            $table->string('interest')->nullable();
            $table->string('category_1')->nullable();
            $table->string('category_2')->nullable();
            $table->integer('twitter_followers')->default(0);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('usertype_id')->references('id')->on('usertypes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
