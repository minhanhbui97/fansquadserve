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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('program', 10);
            $table->tinyInteger('program_level');
            $table->text('description', 5000);
            $table->timestamp('appointment_time');
            $table->timestamps();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('assigned_tutor_id');

            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('assigned_tutor_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
