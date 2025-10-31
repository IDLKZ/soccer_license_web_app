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
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->text('image_url')->nullable();
            $table->string('title_ru');
            $table->string('title_kk');
            $table->string('title_en')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->string('value')->unique();
            $table->boolean('is_active')->default(false);
            $table->integer('level');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leagues');
    }
};
