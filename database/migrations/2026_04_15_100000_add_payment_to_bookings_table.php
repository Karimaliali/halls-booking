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
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('deposit_amount', 10, 2)->nullable()->after('booking_date');
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending')->after('deposit_amount');
            $table->timestamp('payment_date')->nullable()->after('payment_status');
            $table->timestamp('payment_expires_at')->nullable()->after('payment_date');
            $table->string('payment_method')->nullable()->after('payment_expires_at');
            $table->string('transaction_id')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'deposit_amount',
                'payment_status',
                'payment_date',
                'payment_expires_at',
                'payment_method',
                'transaction_id'
            ]);
        });
    }
};
