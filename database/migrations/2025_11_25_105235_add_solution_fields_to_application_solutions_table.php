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
        Schema::table('application_solutions', function (Blueprint $table) {
            $table->string('secretary_position')->nullable()->after('secretary_name');
            $table->string('director_position')->nullable()->after('secretary_position');
            $table->string('director_name')->nullable()->after('director_position');
            $table->string('type')->nullable()->after('director_name');
            $table->json('list_criteria')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_solutions', function (Blueprint $table) {
            $table->dropColumn(['secretary_position', 'director_position', 'director_name', 'type', 'list_criteria']);
        });
    }
};
