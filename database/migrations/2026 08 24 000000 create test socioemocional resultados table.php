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
        Schema::create('test_socioemocional_resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('answers');                       // Respuestas crudas: {1: 4, 2: 3, ...}
            $table->text('reflection')->nullable();         // Reflexión opcional del estudiante
            $table->json('scores');                         // Puntajes RIASEC calculados: {R: 60, I: 80, ...}
            $table->char('primary_dimension', 1);            // Dimensión RIASEC dominante
            $table->char('secondary_dimension', 1);          // Dimensión RIASEC secundaria
            $table->string('carrera_sugerida');
            $table->json('universidades_sugeridas')->nullable();
            $table->timestamps();

            $table->unique('user_id'); // Un resultado vigente por usuario (se sobrescribe si repite el test)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_socioemocional_resultados');
    }
};