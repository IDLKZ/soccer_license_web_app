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
        Schema::table('application_solutions', function (Blueprint $table) {
            $table->json('list_documents')->nullable()->after('application_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_solutions', function (Blueprint $table) {
            $table->dropColumn('list_documents');
        });
    }
};
