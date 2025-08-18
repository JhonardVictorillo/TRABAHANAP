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
        Schema::table('platform_withdrawals', function (Blueprint $table) {
             $table->string('status')->default('processing')->after('admin_id');
            $table->string('stripe_payout_id')->nullable()->after('status');
            $table->text('admin_notes')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platform_withdrawals', function (Blueprint $table) {
            $table->dropColumn(['status', 'stripe_payout_id', 'admin_notes']);
        });
    }
};
