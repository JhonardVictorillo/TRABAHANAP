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
           $table->boolean('is_restricted')->default(false);
        $table->timestamp('restriction_end')->nullable();
        $table->string('restriction_reason')->nullable();
        $table->string('ban_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_restricted', 'restriction_end', 'restriction_reason', 'ban_reason']);
        });
    }
};
