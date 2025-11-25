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
            $table->string('type_ru')->nullable()->after('application_id');
            $table->string('type_kk')->nullable()->after('type_ru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_certificates', function (Blueprint $table) {
            $table->dropColumn(['type_ru', 'type_kk']);
        });
    }
};
