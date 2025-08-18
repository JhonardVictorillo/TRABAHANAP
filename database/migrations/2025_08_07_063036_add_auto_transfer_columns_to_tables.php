<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        // Add auto_transfer_enabled column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('auto_transfer_enabled')->default(false);
        });
        
        // Add appointment_id and is_automatic columns to withdrawals table
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->foreignId('appointment_id')->nullable()->constrained();
            $table->boolean('is_automatic')->default(false);
        });
        
        // Add earnings_transferred column to appointments table
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('earnings_transferred')->default(false);
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('auto_transfer_enabled');
        });
        
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropColumn('appointment_id');
            $table->dropColumn('is_automatic');
        });
        
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('earnings_transferred');
        });
    }
};
