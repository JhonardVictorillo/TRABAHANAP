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
        Schema::table('violation_settings', function (Blueprint $table) {
          $table->integer('warning_threshold')->default(2);
        $table->integer('restriction_threshold')->default(3);
        $table->integer('suspension_threshold')->default(5);
        $table->integer('ban_threshold')->default(7);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violation_settings', function (Blueprint $table) {
            $table->dropColumn('warning_threshold');
            $table->dropColumn('restriction_threshold');
            $table->dropColumn('suspension_threshold');
            $table->dropColumn('ban_threshold');
        });
    }
};
