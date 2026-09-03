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
        Schema::table('becas', function (Blueprint $table) {
            $table->string('titulo_en')->nullable()->after('titulo');
            $table->text('descripcion_en')->nullable()->after('descripcion');
            $table->string('pais_destino_en')->nullable()->after('pais_destino');
            $table->string('nivel_academico_en')->nullable()->after('nivel_academico');
            $table->string('modalidad_en')->nullable()->after('modalidad');
            $table->text('cobertura_resumen_en')->nullable()->after('cobertura_resumen');
            $table->json('requisitos_en')->nullable()->after('requisitos');
            $table->json('carreras_cobertura_en')->nullable()->after('carreras_cobertura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('becas', function (Blueprint $table) {
            $table->dropColumn([
                'titulo_en',
                'descripcion_en',
                'pais_destino_en',
                'nivel_academico_en',
                'modalidad_en',
                'cobertura_resumen_en',
                'requisitos_en',
                'carreras_cobertura_en',
            ]);
        });
    }
};
