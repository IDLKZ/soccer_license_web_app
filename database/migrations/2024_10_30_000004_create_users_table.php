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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->text('image_url')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('username')->unique();
            $table->string('iin', 12)->unique()->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('patronymic')->nullable();
            $table->string('position', 512)->nullable();
            $table->text('password')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('verified')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
