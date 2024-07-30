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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->string('VehicleID')->primary();
            $table->enum('Availability', ['Available', 'Not Available']);
            $table->string('VehicleType');
            $table->string('PlateNo');
            $table->integer('Capacity');
            $table->timestamps();
        });

        Schema::create('drivers', function (Blueprint $table) {
            $table->string('DriverID', 8)->primary();
            $table->string('DriverName', 50);
            $table->string('DriverEmail', 30);
            $table->string('ContactNo', 11);
            $table->enum('Availability', ['Available', 'Not Available']);
            $table->timestamps();
        });

        Schema::create('vehicle_requests', function (Blueprint $table) {
            $table->string('VRequestID', 10)->primary();
            $table->string('DriverID', 9);
            $table->string('VehicleID', 3);
            $table->unsignedBigInteger('BasInID');
            $table->string('PassengerName');
            $table->string('Location', 30);
            $table->string('contact_no',13);
            $table->string('received_by', 50);
            $table->enum('FormStatus', ['Pending', 'Approved', 'Not Approved']);
            $table->enum('EventStatus', ['Cancelled', 'Finished'])->nullable()->default(null);
            $table->timestamps();

            // Adding foreign keys
            $table->foreign('DriverID')->references('DriverID')->on('drivers');
            $table->foreign('VehicleID')->references('VehicleID')->on('vehicles');
            $table->foreign('BasInID')->references('id')->on('some_table_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_requests');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('vehicles');
    }
};
