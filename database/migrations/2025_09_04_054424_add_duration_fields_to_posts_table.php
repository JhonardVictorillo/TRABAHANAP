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
        Schema::table('posts', function (Blueprint $table) {
               $table->integer('service_duration')->default(60)->after('rate_type')->comment('Service duration in minutes');
            $table->integer('buffer_time')->default(15)->after('service_duration')->comment('Buffer time between appointments in minutes');
            $table->enum('scheduling_mode', ['hourly', 'half_day', 'full_day'])->default('hourly')->after('buffer_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
              $table->dropColumn(['service_duration', 'buffer_time', 'scheduling_mode']);
        });
    }
};
