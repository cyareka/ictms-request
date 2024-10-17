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
        Schema::create('offices', function (Blueprint $table) {
            $table->string('OfficeID', 10)->primary();
            $table->string('OfficeName', 255);
            $table->string('OfficeLocation', 30);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->string('EmployeeID', 10)->primary();
            $table->string('EmployeeName', 50);
            $table->string('EmployeeEmail', 50);
            $table->string('OfficeID');
            $table->timestamps();

            // FK
            $table->foreign('OfficeID')->references('OfficeID')->on('offices');
        });
        
        Schema::create('purpose_requests', function (Blueprint $table) {
            $table->string('PurposeID', 3)->primary();
            $table->string('request_p', 20)->nullable();
            $table->string('purpose', 40)->nullable();
            $table->timestamps();
        
        });

        Schema::create('vehicle', function (Blueprint $table) {
            $table->string('VehicleID', 3)->primary();
            $table->string('VehicleType');
            $table->string('PlateNo', 8);
            $table->integer('Capacity');
            $table->boolean('status')->default(1);
            $table->timestamps();
           
        });

        Schema::create('driver', function (Blueprint $table) {
            $table->string('DriverID', 8)->primary();
            $table->string('DriverName', 50);
            $table->string('DriverEmail', 30);
            $table->string('ContactNo', 13);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        Schema::create('AAuthority', function (Blueprint $table) {
            $table->string('AAID', 3)->primary();
            $table->string('AAName', 50);
            $table->string('AAPosition', 50);
            $table->timestamps();
        });

        Schema::create('SOAuthority', function (Blueprint $table) {
            $table->string('SOID', 3)->primary();
            $table->string('SOName', 50);
            $table->string('SOPosition', 50);
            $table->timestamps();
        });

        Schema::create('vehicle_request', function (Blueprint $table) {
            // initial form
            $table->string('VRequestID', 10)->primary();
            $table->string('OfficeID');
            $table->string('PurposeID', 50)->nullable()->default(null);
            $table->string('PurposeOthers')->nullable()->default(null);
            $table->string('date_start', 50);
            $table->string('date_end', 50);
            $table->string('time_start', 50);
            $table->string('Destination', 50);
            $table->string('RequesterName', 50);
            $table->string('RequesterContact',13);
            $table->string('RequesterEmail', 50);
            $table->string('RequesterSignature');
            $table->string('IPAddress',45);
            $table->boolean('is_email_sent')->default(false);
            $table->timestamps();

            // To be filled by dispatcher
            $table->string('DriverID')->nullable()->default(null);
            $table->string('VehicleID')->nullable()->default(null);
//            $table->boolean('VAvailability')->nullable()->default(null);
            $table->unsignedBigInteger('ReceivedBy')->nullable()->default(null);
            $table->string('Remarks')->nullable()->default(null);

            // to be filled by administrative service
            // add availability
            // $table->string('VehicleID')->nullable()->default(null); for availability
            $table->string('AAID')->nullable()->default(null);
            $table->string('SOID')->nullable()->default(null);
            $table->unsignedBigInteger('ASignatory')->nullable()->default(null);
            $table->string('certfile-upload')->nullable()->default(null);
            $table->enum('FormStatus', ['Pending', 'For Approval', 'Approved', 'Not Approved'])->default('Pending');
            $table->enum('EventStatus', ['-', 'Ongoing', 'Finished','Cancelled'])->default('-');

            // Adding foreign keys
            $table->foreign('DriverID')->references('DriverID')->on('driver');
            $table->foreign('VehicleID')->references('VehicleID')->on('vehicle');
            $table->foreign('OfficeID')->references('OfficeID')->on('offices');
            $table->foreign('AAID')->references('AAID')->on('AAuthority');
            $table->foreign('SOID')->references('SOID')->on('SOAuthority');
            $table->foreign('ReceivedBy')->references('id')->on('users');
            $table->foreign('ASignatory')->references('id')->on('users');
            $table->foreign('PurposeID')->references('PurposeID')->on('purpose_requests')->onDelete('cascade');
        });

        Schema::create('vrequest_passenger', function (Blueprint $table) {
            $table->string('VRPassID');
            $table->string('VRequestID');
            $table->string('EmployeeID');
            $table->timestamps();

            // foreign key
            $table->foreign('VRequestID')->references('VRequestID')->on('vehicle_request');
            $table->foreign('EmployeeID')->references('EmployeeID')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AAuthority');
        Schema::dropIfExists('SOAuthority');
        Schema::dropIfExists('driver');
        Schema::dropIfExists('vehicle');
        Schema::dropIfExists('vehicle_request');
        Schema::dropIfExists('vrequest_passenger');

    }
};