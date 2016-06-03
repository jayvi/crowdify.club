<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_agreements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('email')->nullable();
            $table->string('agreement_id')->nullable();
            $table->string('plan_id')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->enum('state',array('Activated','Pending','Expired','Suspended','Reactivated','Cancelled'))->nullable();
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
        Schema::drop('paypal_agreements');
    }
}
