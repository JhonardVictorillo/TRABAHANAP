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
        Schema::create('violation_settings', function (Blueprint $table) {
            $table->id();
            $table->string('user_role'); // freelancer or customer
            $table->boolean('no_show_penalties')->default(true);
            $table->boolean('auto_warning')->default(true);
            $table->boolean('rating_penalty')->default(true);
            $table->boolean('booking_restrictions')->default(true);
            $table->boolean('auto_suspension')->default(true);
            $table->integer('suspension_days')->default(7);
            $table->timestamps();
        });

        
        // Insert default settings
        DB::table('violation_settings')->insert([
            [
                'user_role' => 'freelancer',
                'no_show_penalties' => true,
                'auto_warning' => true,
                'rating_penalty' => true,
                'booking_restrictions' => true,
                'auto_suspension' => true,
                'suspension_days' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_role' => 'customer',
                'no_show_penalties' => true,
                'auto_warning' => true,
                'rating_penalty' => false,
                'booking_restrictions' => true,
                'auto_suspension' => true,
                'suspension_days' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violation_settings');
    }
};
