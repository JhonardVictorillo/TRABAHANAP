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
        Schema::create('freelancer_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('freelancer_id'); // User ID of the freelancer
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('source'); // service_payment, commitment_fee, cancellation_fee
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('freelancer_id')->references('id')->on('users');
            $table->foreign('appointment_id')->references('id')->on('appointments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_earnings');
    }
};
