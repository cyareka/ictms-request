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
        Schema::create('vehicle', function (Blueprint $table) {
            $table->string('VehicleID', 3)->primary();
            $table->string('VehicleType');
            $table->enum('Availability', ['Available', 'Not Available'])->default('Available');
            $table->string('PlateNo', 8);
            $table->integer('Capacity');
            $table->timestamps();
        });

        Schema::create('driver', function (Blueprint $table) {
            $table->string('DriverID', 8)->primary();
            $table->string('DriverName', 50);
            $table->string('DriverEmail', 30);
            $table->string('ContactNo', 13);
            $table->enum('Availability', ['Available', 'Not Available']);
            $table->timestamps();
        });

        Schema::create('vehicle_request', function (Blueprint $table) {
            // initial form
            $table->string('VRequestID', 10)->primary();
            $table->string('OfficeID');
            $table->string('Purpose', 50);
            $table->string('Passengers');
            $table->string('date_start', 10);
            $table->string('date_end', 10);
            $table->string('time_start', 9);
            $table->string('Location', 50);
            $table->string('', 50);
            $table->string('RequesterName');
            $table->string('RequesterContact',13);
            $table->string('RequesterEmail', 20);
            $table->string('RequesterSignature');
            $table->string('IPAddress',45);
            $table->string('ReceivedDate');

            // To be filled by dispatcher
            $table->string('DriverID', 9);
            $table->string('VehicleID', 3);
            $table->string('ReceivedBy')->nullable()->default(null);

            // to be filled by administrative service
            // add availability

            $table->enum('FormStatus', ['Pending', 'Approved', 'Not Approved'])->default('Pending');
            $table->enum('EventStatus', ['-', 'Cancelled', 'Finished'])->nullable()->default('-');
            $table->timestamps();

            // Adding foreign keys
            $table->foreign('DriverID')->references('DriverID')->on('driver');
            $table->foreign('VehicleID')->references('VehicleID')->on('vehicle');
            $table->foreign('OfficeID')->references('OfficeID')->on('offices');
            $table->foreign('Passengers')->references('EmployeeID')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_request');
        Schema::dropIfExists('driver');
        Schema::dropIfExists('vehicle');
    }
};
