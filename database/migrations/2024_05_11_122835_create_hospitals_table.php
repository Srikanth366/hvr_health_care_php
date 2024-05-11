<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_name');
            $table->string('director_name');
            $table->string('email');
            $table->string('hospital_contact_number');
            $table->string('emergency_number');
            $table->string('category');
            $table->string('dmho_licence_number');
            $table->string('accrediations');
            $table->string('experience');
            $table->longText('profile_description');
            $table->string('logo');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('registered_address');
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
        Schema::dropIfExists('hospitals');
    }
}
