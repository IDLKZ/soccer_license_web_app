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
        Schema::table('applications', function (Blueprint $table) {
            $table->boolean('can_reupload_after_ending')->nullable()->after('category_id');
            $table->json('can_reupload_after_endings_doc_ids')->nullable()->after('can_reupload_after_ending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['can_reupload_after_ending', 'can_reupload_after_endings_doc_ids']);
        });
    }
};
