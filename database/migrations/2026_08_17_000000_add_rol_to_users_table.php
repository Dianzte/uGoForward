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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['estudiante', 'padrino'])->nullable()->after('id');
            $table->timestamp('role_selected_at')->nullable()->after('role');
            $table->string('carrera_sugerida')->nullable()->after('role_selected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'role_selected_at', 'carrera_sugerida']);
        });
    }
};