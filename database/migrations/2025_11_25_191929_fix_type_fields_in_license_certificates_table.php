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
        Schema::table('license_certificates', function (Blueprint $table) {
            // Drop old 'type' field if it exists
            if (Schema::hasColumn('license_certificates', 'type')) {
                $table->dropColumn('type');
            }

            // Add new fields if they don't exist
            if (!Schema::hasColumn('license_certificates', 'type_ru')) {
                $table->string('type_ru')->nullable()->after('application_id');
            }
            if (!Schema::hasColumn('license_certificates', 'type_kk')) {
                $table->string('type_kk')->nullable()->after('type_ru');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_certificates', function (Blueprint $table) {
            // Drop new fields
            if (Schema::hasColumn('license_certificates', 'type_ru')) {
                $table->dropColumn('type_ru');
            }
            if (Schema::hasColumn('license_certificates', 'type_kk')) {
                $table->dropColumn('type_kk');
            }

            // Add back old field
            if (!Schema::hasColumn('license_certificates', 'type')) {
                $table->string('type')->nullable()->after('application_id');
            }
        });
    }
};
