<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla que registra las postulaciones de estudiantes a becas.
     */
    public function up(): void
    {
        Schema::create('postulaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('beca_id')->constrained('becas')->cascadeOnDelete();
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');
            $table->timestamp('postulado_at')->useCurrent();
            $table->timestamps();

            // Un estudiante solo puede postularse una vez a cada beca
            $table->unique(['user_id', 'beca_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postulaciones');
    }
};
