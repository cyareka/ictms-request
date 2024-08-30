<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

    class CreateFocalPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('focal_person', function (Blueprint $table) {
            $table->string('FocalPID', 3)->primary();
            $table->string('FPName')->nullable();  // Name of the focal point
            $table->string('OfficeID');  // Foreign key to the offices table
            $table->timestamps();  // Created at and Updated at timestamps

            $table->foreign('OfficeID')->references('OfficeID')->on('offices');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('focal_person');
    }
}