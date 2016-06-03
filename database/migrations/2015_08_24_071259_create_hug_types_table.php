<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHugTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hug_types', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('hug_type', array('Visit Url', 'Twitter Post', 'Facebook Post'))->default('Visit Url');
            $table->string('description', 500)->nullable();
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
        Schema::drop('hug_types');
    }
}
