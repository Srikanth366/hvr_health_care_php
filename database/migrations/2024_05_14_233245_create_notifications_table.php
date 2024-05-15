<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            $table->index('id');
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');

        /*
        DELIMITER $$

CREATE TRIGGER after_hvr_doctors_insert
AFTER INSERT ON hvr_doctors
FOR EACH ROW
BEGIN
    INSERT INTO notifications (user_id, type, message, is_read, created_at, updated_at)
    VALUES (NEW.id, 'new_doctor', CONCAT('A new doctor with ID ', NEW.id, ' has been added.'), FALSE, NOW(), NOW());
END$$

DELIMITER ;
        */
    }
}
