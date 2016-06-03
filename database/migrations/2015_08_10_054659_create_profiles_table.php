<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('provider_id',255);
            $table->string('token',255)->nullable();
            $table->string('token_secret',255)->nullable();
            $table->string('name',60)->nullable();
            $table->string('username',60)->nullable();
            $table->string('avatar')->nullable();
            $table->enum('provider',[
                'github','twitter','facebook','google','linkedin','foursquare','flickr','instagram','pinterest','youtube','blogger'
            ])->default(null);
            $table->boolean('active')->default(true);
            $table->string('social_profile_url')->nullable();
            $table->integer('social_count')->default(0);
            $table->string('affiliate',60)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profiles');
    }
}
