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
            $table->string('url_oficial')->unique()->after('titulo')->nullable();

            $table->string('nivel_academico')->nullable()->after('descripcion');
            $table->string('modalidad')->default('Presencial')->after('nivel_academico');
            $table->text('cobertura_resumen')->nullable()->after('modalidad');
            $table->json('requisitos')->nullable()->after('cobertura_resumen');
            $table->json('carreras_cobertura')->nullable()->after('requisitos');
            $table->string('cum_promedio_minimo', 10)->nullable()->after('carreras_cobertura');
            $table->enum('estado', ['Activa', 'Cerrada', 'En Revision'])->default('Activa')->after('cum_promedio_minimo');

            $table->text('descripcion')->nullable()->change();
            $table->date('vencimiento')->nullable()->change();
            $table->unsignedBigInteger('carrera_id')->nullable()->change();
            $table->unsignedBigInteger('condicion_id')->nullable()->change();
            $table->unsignedBigInteger('ayuda_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
