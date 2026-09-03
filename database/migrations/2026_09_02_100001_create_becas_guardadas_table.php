<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de becas guardadas/favoritos por usuario.
     */
    public function up(): void
    {
        Schema::create('becas_guardadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('beca_id')->constrained('becas')->cascadeOnDelete();
            $table->timestamps();

            // Restricción única: un usuario solo puede guardar una beca una vez
            $table->unique(['user_id', 'beca_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('becas_guardadas');
    }
};
