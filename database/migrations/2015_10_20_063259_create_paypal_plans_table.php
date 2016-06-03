<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan_id')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->enum('type',array('FIXED','INFINITE'))->default('INFINITE');
            $table->enum('state',array('CREATED','ACTIVE','INACTIVE'))->default('CREATED');
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
        Schema::drop('paypal_plans');
    }
}
