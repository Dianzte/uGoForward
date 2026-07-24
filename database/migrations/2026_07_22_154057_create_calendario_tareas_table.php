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
    Schema::create('calendario_tareas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Relación exacta con la tabla 'becas' de tu compañero
        $table->foreignId('beca_id')->nullable()->constrained('becas')->onDelete('cascade');
        
        $table->string('titulo');
        $table->date('fecha');
        $table->boolean('completado')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendario_tareas');
    }
};
