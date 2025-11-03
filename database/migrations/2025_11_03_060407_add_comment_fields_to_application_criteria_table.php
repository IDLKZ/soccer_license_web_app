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
        Schema::table('application_criteria', function (Blueprint $table) {
            $table->text('first_comment')->nullable()->after('is_first_passed');
            $table->text('industry_comment')->nullable()->after('is_industry_passed');
            $table->text('final_comment')->nullable()->after('is_final_passed');
            $table->text('last_comment')->nullable()->after('final_comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_criteria', function (Blueprint $table) {
            $table->dropColumn(['first_comment', 'industry_comment', 'final_comment', 'last_comment']);
        });
    }
};
