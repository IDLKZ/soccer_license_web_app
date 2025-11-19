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
        Schema::create('application_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('application_status_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('previous_id')
                ->nullable()
                ->constrained('application_statuses')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('next_id')
                ->nullable()
                ->constrained('application_statuses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('title_ru');
            $table->string('title_kk');
            $table->string('title_en')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->string('value')->unique();
            $table->json('role_values')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_first')->default(false);
            $table->boolean('is_last')->default(false);
            $table->integer('result')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_statuses');
    }
};
