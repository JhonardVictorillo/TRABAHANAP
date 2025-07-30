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
        Schema::create('platform_withdrawals', function (Blueprint $table) {
             $table->id();
        $table->decimal('amount', 10, 2);
        $table->string('payment_method');
        $table->string('bank_name');
        $table->string('account_number');
        $table->string('reference_number')->nullable();
        $table->text('notes')->nullable();
        $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamp('processed_at')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_withdrawals');
    }
};
