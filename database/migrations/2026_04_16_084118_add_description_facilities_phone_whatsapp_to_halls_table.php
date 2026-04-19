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
            if (! Schema::hasColumn('halls', 'description')) {
                $table->text('description')->nullable();
            }
            if (! Schema::hasColumn('halls', 'facilities')) {
                $table->text('facilities')->nullable();
            }
            if (! Schema::hasColumn('halls', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (! Schema::hasColumn('halls', 'whatsapp')) {
                $table->string('whatsapp')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('halls', function (Blueprint $table) {
            if (Schema::hasColumn('halls', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('halls', 'facilities')) {
                $table->dropColumn('facilities');
            }
            if (Schema::hasColumn('halls', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('halls', 'whatsapp')) {
                $table->dropColumn('whatsapp');
            }
        });
    }
};
