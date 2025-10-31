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
        Schema::create('application_documents', function (Blueprint $table) {
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
            $table->foreignId('document_id')
                ->nullable()
                ->constrained('documents')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->text('file_url');

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
            $table->boolean('is_first_passed')->nullable();
            $table->boolean('is_industry_passed')->nullable();
            $table->boolean('is_final_passed')->nullable();

            // Document info
            $table->text('title');
            $table->text('info')->nullable();

            // Comments from different stages
            $table->text('first_comment')->nullable();
            $table->text('industry_comment')->nullable();
            $table->text('control_comment')->nullable();

            $table->timestamp('deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};
