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
        Schema::create('basic_information', function (Blueprint $table) {
            $table->string('BasInID', 9)->primary();
            $table->string('RequesterName', 50);
            $table->string('RequesterEmail', 50);
            $table->string('requested_by', 50);
            $table->string('RequestingOffice', 50);
            $table->string('Purpose', 100);
            $table->string('date_start', 10);
            $table->string('date_end', 10);
            $table->string('time_start', 4);
            $table->string('time_end', 4);
            $table->string('RequesterSignature'); // Use string to store file path or name
            $table->date('ReceivedDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_information');
    }
};
