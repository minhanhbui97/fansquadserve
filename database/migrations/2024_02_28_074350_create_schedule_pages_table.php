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
        Schema::create('schedule_pages', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_url');
            $table->unsignedBiginteger('tutor_id');
            $table->timestamps();


            $table->foreign('tutor_id')->references('id')
            ->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_pages');
    }
};
