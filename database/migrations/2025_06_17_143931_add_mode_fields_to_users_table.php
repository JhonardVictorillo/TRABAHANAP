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
            // Currently active mode (this is the new field we're adding)
        // Make current_mode nullable with no default
        $table->string('current_mode')->nullable()->after('role');
        
        // Optional: Track when user changed roles
        $table->timestamp('role_updated_at')->nullable();
        
        // Optional: onboarding status tracking
        $table->boolean('freelancer_onboarded')->default(false);
        $table->boolean('customer_onboarded')->default(false);
        });

         DB::statement('UPDATE users SET current_mode = role WHERE current_mode IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['current_mode', 'role_updated_at', 'freelancer_onboarded', 'customer_onboarded']);
        });
    }
};
