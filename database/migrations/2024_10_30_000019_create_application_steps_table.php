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
        Schema::create('application_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')
                ->nullable()
                ->constrained('applications')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('application_criteria_id')
                ->nullable()
                ->constrained('application_criteria')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('status_id')
                ->nullable()
                ->constrained('application_statuses')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('responsible_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->text('file_url')->nullable();
            $table->text('responsible_by')->nullable();
            $table->boolean('is_passed')->nullable();
            $table->text('result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_steps');
    }
};
