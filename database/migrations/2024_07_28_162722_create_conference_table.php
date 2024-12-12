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
        Schema::create('focal_person', function (Blueprint $table) {
            $table->string('FocalPID', 3)->primary();
            $table->string('FPName', 50)->nullable();
            $table->string('OfficeID');
            $table->timestamps();

            $table->foreign('OfficeID')->references('OfficeID')->on('offices');
        });

        Schema::create('superior', function (Blueprint $table) {
            $table->string('SuperiorID', 3)->primary();
            $table->string('SName', 50)->nullable();
            $table->string('Designation', 30);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

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
            $table->string('date_start');
            $table->string('date_end');
            $table->string('time_start');
            $table->string('time_end');
            $table->integer('npersons');
            $table->unsignedBigInteger('AuthRep')->nullable()->default(null);
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
