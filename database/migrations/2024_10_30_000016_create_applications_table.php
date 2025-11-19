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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('license_id')
                ->nullable()
                ->constrained('licences')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('club_id')
                ->nullable()
                ->constrained('clubs')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('application_status_categories')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->boolean('is_ready')->default(false);
            $table->boolean('is_active')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
