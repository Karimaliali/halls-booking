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
        Schema::table('halls', function (Blueprint $table) {
            if (! Schema::hasColumn('halls', 'min_price')) {
                $table->decimal('min_price', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('halls', 'max_price')) {
                $table->decimal('max_price', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('halls', 'status')) {
                $table->string('status')->default('متاح');
            }
            if (! Schema::hasColumn('halls', 'unavailable_dates')) {
                $table->json('unavailable_dates')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('halls', function (Blueprint $table) {
            if (Schema::hasColumn('halls', 'min_price')) {
                $table->dropColumn('min_price');
            }
            if (Schema::hasColumn('halls', 'max_price')) {
                $table->dropColumn('max_price');
            }
            if (Schema::hasColumn('halls', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('halls', 'unavailable_dates')) {
                $table->dropColumn('unavailable_dates');
            }
        });
    }
};
