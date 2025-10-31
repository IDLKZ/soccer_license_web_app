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
        Schema::create('club_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')
                ->constrained('clubs')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();

            // Add unique constraint to prevent duplicate club-role-user combinations
            $table->unique(['club_id', 'role_id', 'user_id'], 'club_team_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_team');
    }
};
