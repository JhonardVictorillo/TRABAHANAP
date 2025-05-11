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
            $table->string('day_of_week')->nullable()->change(); // Make column nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('freelancer_availabilities', function (Blueprint $table) {
            $table->string('day_of_week')->nullable(false)->change(); // Revert to NOT NULL
        });
    }
};
