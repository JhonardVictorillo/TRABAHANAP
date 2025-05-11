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
        Schema::table('appointments', function (Blueprint $table) {
            $table->decimal('commitment_fee', 8, 2)->default(0); // Fee amount
        $table->enum('fee_status', ['pending', 'paid', 'forfeited', 'refunded'])->default('pending'); // Fee status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['commitment_fee', 'fee_status']);
        });
    }
};
