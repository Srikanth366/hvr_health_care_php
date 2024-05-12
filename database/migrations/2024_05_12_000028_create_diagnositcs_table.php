<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnositcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnositcs', function (Blueprint $table) {
            $table->id();
            $table->string('diagnostics_name');
            $table->string('owner_name');
            $table->string('gender');
            $table->string('email');
            $table->string('mobile');
            $table->string('Category');
            $table->string('licence_number');
            $table->string('accrediations_NABL');
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
        Schema::dropIfExists('diagnositcs');
    }
}
