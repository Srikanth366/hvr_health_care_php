<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy', function (Blueprint $table) {
            $table->id();
            $table->string('pharmacy_name');
            $table->string('pharmacist_name');
            $table->string('gender');
            $table->string('email');
            $table->string('mobile');
            $table->string('Category');
            $table->string('drug_licence_number');
            $table->string('experience');
            $table->longText('profile_description');
            $table->string('logo');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('registered_address');
            $table->int('status')->default(1);
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
        Schema::dropIfExists('pharmacies');
    }
}
