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
            $table->string('category')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('google_map_link')->nullable();
            $table->string('id_front')->nullable(); // Column for front ID upload
            $table->string('id_back')->nullable(); // Column for back ID upload
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['category', 'province', 'city', 'zipcode', 'google_map_link','id_front', 'id_back']);
        });
    }
};
