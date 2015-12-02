<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('from_user_id');
            $table->foreign('from_user_id')->references('id')->on('users');
            $table->unsignedInteger('to_user_id');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_confirmed')->default(false);
            $table->unique(array('from_user_id', 'to_user_id', 'start_date', 'end_date'));
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
        Schema::drop('appointment_requests');
    }
}
