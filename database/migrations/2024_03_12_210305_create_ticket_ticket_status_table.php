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
        Schema::create('ticket_ticket_status', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBiginteger('ticket_status_id');
            $table->unsignedBiginteger('ticket_id');

            $table->foreign('ticket_status_id')->references('id')
                 ->on('ticket_statuses')->onDelete('cascade');
            $table->foreign('ticket_id')->references('id')
                ->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_ticket_status');
    }
};
