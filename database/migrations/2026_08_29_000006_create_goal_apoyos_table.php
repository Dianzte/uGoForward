<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goal_apoyos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('goal_id')->constrained('goals')->onDelete('cascade');
            $table->string('mensaje')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'goal_id']); // Un apoyo por usuario por meta
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_apoyos');
    }
};
