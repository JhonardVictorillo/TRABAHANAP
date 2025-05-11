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
        Schema::table('freelancer_availabilities', function (Blueprint $table) {
            $table->date('date')->nullable()->after('freelancer_id'); // Allow NULL values
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('freelancer_availabilities', function (Blueprint $table) {
            $table->dropColumn('date'); // Remove the date column
        });
    }
};
