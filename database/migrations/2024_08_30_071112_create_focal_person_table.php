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
            $table->string('FPName')->nullable();
            $table->string('OfficeID');
            $table->boolean('is_custom')->default(false);
            $table->timestamps();

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
