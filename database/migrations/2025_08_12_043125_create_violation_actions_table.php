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
        Schema::create('violation_actions', function (Blueprint $table) {
             $table->id();
            $table->foreignId('violation_id')->constrained('violations');
            $table->string('action_type'); // warning, suspension, fee, etc.
            $table->text('notes')->nullable();
            $table->json('action_data')->nullable(); // For storing action-specific details
            $table->foreignId('admin_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violation_actions');
    }
};
