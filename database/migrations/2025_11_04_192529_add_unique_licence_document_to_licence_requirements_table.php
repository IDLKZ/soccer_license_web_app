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
        Schema::table('licence_requirements', function (Blueprint $table) {
            // Add unique constraint for licence_id and document_id combination
            $table->unique(['licence_id', 'document_id'], 'licence_requirements_licence_document_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licence_requirements', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('licence_requirements_licence_document_unique');
        });
    }
};
