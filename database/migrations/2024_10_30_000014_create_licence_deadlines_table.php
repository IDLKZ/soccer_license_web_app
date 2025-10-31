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
        Schema::create('licence_deadlines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licence_id')
                ->nullable()
                ->constrained('licences')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('club_id')
                ->nullable()
                ->constrained('clubs')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->date('start_at');
            $table->date('end_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licence_deadlines');
    }
};
