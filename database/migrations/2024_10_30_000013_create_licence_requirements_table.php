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
        Schema::create('licence_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licence_id')
                ->nullable()
                ->constrained('licences')
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
            $table->boolean('is_required')->default(true);
            $table->json('allowed_extensions')->nullable();
            $table->decimal('max_file_size_mb', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licence_requirements');
    }
};
