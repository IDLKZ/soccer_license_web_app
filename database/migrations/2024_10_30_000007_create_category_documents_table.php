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
        Schema::create('category_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title_ru');
            $table->string('title_kk');
            $table->string('title_en')->nullable();
            $table->string('value')->unique();
            $table->integer('level');
            $table->json('roles')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_documents');
    }
};
