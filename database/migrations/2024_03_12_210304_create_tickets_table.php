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
            $table->string('reference_number', 20);
            $table->tinyInteger('program_level')->nullable();
            $table->text('description', 500);
            $table->timestamp('scheduled_start_time');
            $table->timestamp('scheduled_end_time');
            $table->timestamp('actual_start_time')->nullable();
            $table->timestamp('actual_end_time')->nullable();
            $table->text('tutor_note', 500)->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('assigned_tutor_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('type_of_machine_id')->nullable();
            $table->unsignedBigInteger('operating_system_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->unsignedBigInteger('program_id');

            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('program_id')->references('id')->on('programs');
            $table->foreign('type_of_machine_id')->references('id')->on('type_of_machines');
            $table->foreign('operating_system_id')->references('id')->on('operating_systems');
            $table->foreign('priority_id')->references('id')->on('priorities');
            $table->foreign('course_id')->references('id')->on('courses');
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
