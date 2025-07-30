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
           $table->decimal('total_amount', 10, 2)->nullable()->after('commitment_fee');
        $table->enum('final_payment_status', ['pending', 'paid', 'disputed'])->default('pending')->after('fee_status');
        $table->string('final_stripe_session_id')->nullable()->after('stripe_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('appointments', function (Blueprint $table) {
        $table->dropColumn('total_amount');
        $table->dropColumn('final_payment_status');
        $table->dropColumn('final_stripe_session_id');
    });
    }
};
