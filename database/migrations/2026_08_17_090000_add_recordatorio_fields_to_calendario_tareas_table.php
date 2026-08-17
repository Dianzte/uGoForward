<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega los campos necesarios para el sistema de recordatorios
     * a la tabla calendario_tareas existente.
     *
     * Campos añadidos:
     *   - hora_evento      : Hora del evento (nullable). Permite recordatorios exactos.
     *   - descripcion      : Descripción opcional de la tarea/evento.
     *   - recordatorio_minutos : Minutos de anticipación para el recordatorio (default 30).
     *   - recordatorio_enviado : Flag para evitar envíos duplicados (default false).
     */
    public function up(): void
    {
        Schema::table('calendario_tareas', function (Blueprint $table) {
            $table->time('hora_evento')->nullable()->after('fecha')
                  ->comment('Hora del evento. Si es null se usa 00:00 como base.');
            $table->text('descripcion')->nullable()->after('hora_evento')
                  ->comment('Descripción opcional del evento o tarea.');
            $table->unsignedInteger('recordatorio_minutos')->default(30)->after('descripcion')
                  ->comment('Minutos antes del evento para enviar el recordatorio.');
            $table->boolean('recordatorio_enviado')->default(false)->after('recordatorio_minutos')
                  ->comment('Previene el envío duplicado de recordatorios.');
        });
    }

    /**
     * Revierte los cambios en la tabla calendario_tareas.
     */
    public function down(): void
    {
        Schema::table('calendario_tareas', function (Blueprint $table) {
            $table->dropColumn([
                'hora_evento',
                'descripcion',
                'recordatorio_minutos',
                'recordatorio_enviado',
            ]);
        });
    }
};
