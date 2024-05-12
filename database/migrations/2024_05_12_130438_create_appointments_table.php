<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('PatientID');
            $table->bigInteger('DoctorID');
            $table->date('AppointmentDate');
            $table->time('AppointmentTime');
            $table->enum('status', ['Requested', 'Confirmed', 'Cancelled','Completed'])->default('Requested');
            $table->string('doctor_type');
            $table->text('Notes');
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
        Schema::dropIfExists('appointments');
    }
}
