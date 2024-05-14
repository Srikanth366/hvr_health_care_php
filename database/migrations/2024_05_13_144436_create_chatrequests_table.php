<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatrequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatrequests', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_type');
            $table->string('patientID');
            $table->string('doctorID');
            $table->integer('status')->default(0); // 0-pending 1-Accepted 2 - Rejected 3 - Blocked
            $table->string('notes', 500)->default('none'); 
            $table->integer('user_chat_status')->default(0); // 0 - Allow 1 - Block
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
        Schema::dropIfExists('chatrequests');
    }
}
