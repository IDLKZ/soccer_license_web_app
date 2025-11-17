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
        Schema::create('application_criteria_deadline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('application_criteria_id')->constrained('application_criteria')->onDelete('cascade');
            $table->dateTime('deadline_start_at')->nullable();
            $table->dateTime('deadline_end_at');
            $table->foreignId('status_id')->constrained('application_statuses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_criteria_deadline');
    }
};
