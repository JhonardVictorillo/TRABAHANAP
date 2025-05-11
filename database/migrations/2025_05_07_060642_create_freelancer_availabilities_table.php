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
        Schema::create('freelancer_availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('freelancer_id'); // Foreign key to users table
            $table->string('day_of_week'); // e.g., Monday, Tuesday
            $table->time('start_time'); // Start time of availability
            $table->time('end_time'); // End time of availability
            $table->timestamps();

            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_availabilities');
    }
};
