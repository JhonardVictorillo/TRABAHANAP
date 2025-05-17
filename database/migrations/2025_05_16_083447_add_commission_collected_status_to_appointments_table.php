<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
           DB::statement("ALTER TABLE appointments MODIFY COLUMN fee_status ENUM('pending', 'paid', 'forfeited', 'refunded', 'commission_collected') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
              DB::statement("ALTER TABLE appointments MODIFY COLUMN fee_status ENUM('pending', 'paid', 'forfeited', 'refunded') DEFAULT 'pending'");
        });
    }
};
