<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTicketInfoIntoEventRegistrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_registrants', function (Blueprint $table) {
            $table->integer('event_ticket_id')->unsigned()->nullable();
            $table->integer('number_of_tickets')->default(0);

            $table->foreign('event_ticket_id')->references('id')->on('event_tickets')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_registrants', function (Blueprint $table) {
            $table->dropForeign('event_registrants_event_ticket_id_foreign');
            $table->dropColumn('number_of_tickets');
        });
    }
}
