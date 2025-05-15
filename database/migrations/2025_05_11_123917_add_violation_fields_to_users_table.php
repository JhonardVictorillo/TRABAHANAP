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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('no_show_count')->default(0);
            $table->unsignedInteger('late_cancel_count')->default(0);
            $table->unsignedInteger('violation_count')->default(0);
            $table->timestamp('last_violation_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['no_show_count', 'late_cancel_count', 'violation_count', 'last_violation_at']);
        });
    }
};
