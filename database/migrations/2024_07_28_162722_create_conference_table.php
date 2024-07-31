<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conference_rooms', function (Blueprint $table) {
            $table->string('CRoomID', 4)->primary();
            $table->enum('Availability', ['Available', 'Not Available']);
            $table->enum('CRoomName', ['Magiting', 'Maagap']);
            $table->string('Location', 50);
            $table->integer('Capacity')->unsigned();
            $table->timestamps();
        });

        Schema::create('conference_room_requests', function (Blueprint $table) {
            $table->string('CRequestID')->primary();
            $table->string('BasInID');
            $table->string('CRoomID');
            $table->string('EquipID')->nullable();
            $table->string('Purpose');
            $table->integer('npersons');
            $table->string('focalPerson');
            $table->integer('tables');
            $table->integer('chairs');
            $table->string('otherFacilities');
            $table->enum('FormStatus', ['Pending', 'Approved', 'Not Approved']);
            $table->enum('EventStatus', ['Cancelled', 'Finished'])->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('CRoomID')->references('CRoomID')->on('conference_rooms')->onDelete('cascade');
            $table->foreign('EquipID')->references('EquipID')->on('equipment')->onDelete('set null');
            $table->foreign('BasInID')->references('id')->on('some_table_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conference_room_requests');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('conference_rooms');
    }
};