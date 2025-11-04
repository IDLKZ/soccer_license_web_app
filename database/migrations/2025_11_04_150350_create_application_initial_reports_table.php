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
        Schema::create('application_initial_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')
                ->nullable()
                ->constrained('applications')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('criteria_id')
                ->nullable()
                ->constrained('application_criteria')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_initial_reports');
    }
};
