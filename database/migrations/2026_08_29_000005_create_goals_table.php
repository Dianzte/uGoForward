<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['en_progreso', 'completada', 'abandonada'])->default('en_progreso');
            $table->unsignedTinyInteger('progreso')->default(0); // 0-100
            $table->boolean('es_publica')->default(true);
            $table->date('fecha_limite')->nullable();
            $table->unsignedInteger('apoyos_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
