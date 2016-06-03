<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliateEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_earnings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('affiliate_id')->unsigned()->nullable();
            $table->integer('affiliate_reference_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->double('amount')->default(0);
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('payment_completion_date')->nullable();
            $table->enum('status',array('Paid','Pending','Cancelled'))->default('Pending');
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
        Schema::drop('affiliate_earnings');
    }
}
