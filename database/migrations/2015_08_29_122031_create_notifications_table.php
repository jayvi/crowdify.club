<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id')->unsigned();
            $table->integer('recipient_id')->unsigned();
            $table->enum('type', array('HUG-COMPLETED','HUG-COMPLETION-APPROVED','CROWD-COIN-GIFT','COMMENT','SHARE-PURCHASED',''))->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->boolean('seen')->default(false);
            $table->string('target_link')->nullable();
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notifications');
    }
}
