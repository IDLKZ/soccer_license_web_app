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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('category_documents')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->text('example_file_url')->nullable();
            $table->string('title_ru');
            $table->string('title_kk');
            $table->string('title_en')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->string('value')->unique();
            $table->integer('level');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
