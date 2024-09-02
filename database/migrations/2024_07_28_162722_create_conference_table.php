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
            $table->string('CRoomID', 10)->primary();
            $table->string('CRoomName', 50);
            $table->string('Location', 50);
            $table->integer('Capacity')->unsigned();
            $table->timestamps();
        });

        // remove BasInID and add OfficeID as well as Purpose - DateEnd from basic_information table
        Schema::create('conference_room_requests', function (Blueprint $table) {
            $table->string('CRequestID', 10)->primary();
            $table->string('OfficeID');
            $table->string('PurposeID')->nullable()->default(null);
            $table->string('PurposeOthers')->nullable()->default(null);
            $table->string('date_start', 10);
            $table->string('date_end', 10);
            $table->string('time_start', 9);
            $table->string('time_end', 9);
            $table->integer('npersons');
            $table->string('FocalPID')->nullable()->default(null);
            $table->string('FPOthers')->nullable()->default(null);
            $table->boolean('CAvailability')->default(true)->nullable();
            $table->integer('tables')->nullable();
            $table->integer('chairs')->nullable();
            $table->string('otherFacilities', 50)->nullable();
            $table->string('CRoomID');
            $table->string('RequesterName', 50);
            $table->string('RequesterSignature');
            $table->string('certfile-upload')->nullable()->default(null);
            $table->enum('FormStatus', ['Pending', 'For Approval', 'Approved', 'Not Approved'])->default('Pending');
            $table->enum('EventStatus', ['-', 'Ongoing', 'Cancelled', 'Finished'])->default('-');
            $table->timestamps();

            // Foreign keys
            $table->foreign('CRoomID')->references('CRoomID')->on('conference_rooms')->onDelete('cascade');
            $table->foreign('OfficeID')->references('OfficeID')->on('offices')->onDelete('cascade');
            $table->foreign('PurposeID')->references('PurposeID')->on('purpose_requests')->onDelete('cascade');
            $table->foreign('FocalPID')->references('FocalPID')->on('focal_person')->onDelete('cascade');
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
