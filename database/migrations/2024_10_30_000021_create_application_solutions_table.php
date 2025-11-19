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
        Schema::create('application_solutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')
                ->nullable()
                ->constrained('applications')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('secretary_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->string('secretary_name')->nullable();
            $table->date('meeting_date')->nullable();
            $table->string('meeting_place', 512)->nullable();
            $table->string('department_name', 512)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_solutions');
    }
};
