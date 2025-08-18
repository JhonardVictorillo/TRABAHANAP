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
        Schema::create('violations', function (Blueprint $table) {
             $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('user_role'); // 'freelancer' or 'customer'
            $table->string('violation_type'); // 'no_show', 'late_cancel', etc.
            $table->foreignId('appointment_id')->nullable()->constrained('appointments');
            $table->text('notes')->nullable();
            $table->string('status')->default('active'); // active, resolved, dismissed
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
