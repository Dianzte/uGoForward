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
            $table->foreignId('carrera_id')->constrained('carreras')->onDelete('cascade');
            $table->foreignId('condicion_id')->constrained('condiciones')->onDelete('cascade');
            $table->foreignId('ayuda_id')->constrained('ayuda')->onDelete('cascade');
            $table->datetime('vencimiento');
            $table->foreignId('imagen_id')->constrained('imagenes')->onDelete('cascade');
            


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('becas', function (Blueprint $table) {
            //
        });
    }
};
