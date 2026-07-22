<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('foros', function (Blueprint $table) {
            $table->id();

            $table->foreignId('universidad_id')->nullable()->constrained('universidades')->onDelete('set null');

            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('contenido');

            $table->foreignId('categoriasforo_id');
            $table->foreignId('carreras_id'); 

            $table->integer('visitas_count')->default(0);
            $table->integer('reportes_count')->default(0);

            $table->timestamps();
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
