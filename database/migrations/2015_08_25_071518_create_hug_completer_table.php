<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHugCompleterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hug_completer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hug_id')->unsigned();
            $table->integer('completer_id')->unsigned();
            $table->boolean('approved')->default(false);
            $table->timestamps();

            $table->foreign('hug_id')->references('id')->on('hugs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('completer_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hug_completer');
    }
}
