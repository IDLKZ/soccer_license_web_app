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
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->text('image_url')->nullable();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('clubs')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('type_id')
                ->nullable()
                ->constrained('club_types')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->text('full_name_ru');
            $table->text('full_name_kk');
            $table->text('full_name_en')->nullable();
            $table->string('short_name_ru');
            $table->string('short_name_kk');
            $table->string('short_name_en')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->string('bin', 12)->unique();
            $table->date('foundation_date');
            $table->text('legal_address');
            $table->text('actual_address');
            $table->text('website')->nullable();
            $table->text('email')->nullable();
            $table->text('phone_number')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
