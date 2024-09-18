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
        Schema::create('superior', function (Blueprint $table) {
            $table->string('SuperiorID', 3)->primary();
            $table->string('SName')->nullable();
            $table->string('Designation');
            $table->boolean('status')->default(1)->change();
            $table->string('term_start');
            $table->string('term_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('superior');
    }
};
