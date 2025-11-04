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
        Schema::create('application_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')
                ->nullable()
                ->constrained('applications')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('category_documents')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('status_id')
                ->nullable()
                ->constrained('application_statuses')
                ->onDelete('set null')
                ->onUpdate('cascade');

            // Uploaded by
            $table->foreignId('uploaded_by_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->text('uploaded_by')->nullable();

            // First check
            $table->foreignId('first_checked_by_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->text('first_checked_by')->nullable();

            // Regular check
            $table->foreignId('checked_by_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->text('checked_by')->nullable();

            // Control check
            $table->foreignId('control_checked_by_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->text('control_checked_by')->nullable();

            // Status flags
            $table->boolean('is_ready')->default(false);
            $table->boolean('is_first_passed')->nullable();
            $table->text('first_comment')->nullable();
            $table->boolean('is_industry_passed')->nullable();
            $table->text('industry_comment')->nullable();
            $table->boolean('is_final_passed')->nullable();
            $table->text('final_comment')->nullable();
            $table->text('last_comment')->nullable();
            $table->boolean('can_reupload_after_ending')->nullable();
            $table->json('can_reupload_after_endings_doc_ids')->nullable();
            $table->timestamps();

            // Unique constraint: application_id + category_id must be unique
            $table->unique(['application_id', 'category_id'], 'unique_application_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_criteria');
    }
};
