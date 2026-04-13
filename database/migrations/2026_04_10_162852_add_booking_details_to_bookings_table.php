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
            $table->string('user_name')->nullable();
            $table->string('user_id_number')->nullable();
            $table->string('id_card_image')->nullable();
            $table->string('receipt_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['user_name', 'user_id_number', 'id_card_image', 'receipt_image']);
        });
    }
};
