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
            if (!Schema::hasColumn('users', 'skills')) {
                $table->json('skills')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'specialization')) {
                $table->string('specialization')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'hourly_rate')) {
                $table->decimal('hourly_rate', 10, 2)->nullable();
            }
            
            if (!Schema::hasColumn('users', 'daily_rate')) {
                $table->decimal('daily_rate', 10, 2)->nullable();
            }
            
            if (!Schema::hasColumn('users', 'category_request')) {
                $table->string('category_request')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropColumn([
                'skills',
                'specialization',
                'hourly_rate',
                'daily_rate',
                'category_request'
            ]);
        });
    }
};
