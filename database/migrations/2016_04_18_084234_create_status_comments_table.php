<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('commenter_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('commenter_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status_updates')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('status_comments');
    }
}
