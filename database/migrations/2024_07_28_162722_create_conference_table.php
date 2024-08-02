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
            $table->string('CRoomID', 3)->primary();
            $table->enum('Availability', ['Available', 'Not Available'])->default('Available');
            $table->string('CRoomName', 50);
            $table->string('Location', 50);
            $table->integer('Capacity')->unsigned();
            $table->timestamps();
        });

        // remove BasInID and add OfficeID as well as Purpose - DateEnd from basic_information table
        Schema::create('conference_room_requests', function (Blueprint $table) {
            $table->string('CRequestID', 10)->primary();
            $table->string('BasInID');
            $table->string('CRoomID');
            $table->integer('npersons');
            $table->string('focalPerson');
            $table->integer('tables');
            $table->integer('chairs');
            $table->string('otherFacilities')->nullable();
            $table->enum('FormStatus', ['Pending', 'Approved', 'Not Approved']);
            $table->enum('EventStatus', ['Cancelled', 'Finished'])->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('CRoomID')->references('CRoomID')->on('conference_rooms')->onDelete('cascade');
            $table->foreign('BasInID')->references('id')->on('basic_information');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conference_room_requests');
        Schema::dropIfExists('conference_rooms');
    }
};
