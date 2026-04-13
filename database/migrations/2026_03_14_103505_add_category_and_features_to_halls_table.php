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
            if (! Schema::hasColumn('halls', 'category')) {
                $table->string('category')->nullable()->after('capacity');
            }
            if (! Schema::hasColumn('halls', 'features')) {
                $table->json('features')->nullable()->after('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('halls', function (Blueprint $table) {
            if (Schema::hasColumn('halls', 'features')) {
                $table->dropColumn('features');
            }
            if (Schema::hasColumn('halls', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
