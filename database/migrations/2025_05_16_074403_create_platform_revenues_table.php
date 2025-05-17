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
        Schema::create('platform_revenues', function (Blueprint $table) {
           $table->id();
        $table->decimal('amount', 10, 2);
        $table->string('source'); // commitment_fee, subscription, etc.
        $table->unsignedBigInteger('appointment_id')->nullable();
        $table->unsignedBigInteger('user_id')->nullable(); // For tracking which user generated the revenue
        $table->date('date');
        $table->text('notes')->nullable();
        $table->timestamps();
        
        $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('set null');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_revenues');
    }
};
