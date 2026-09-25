<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega soporte para salas de chat privadas de beca (estudiante ↔ padrino/admin).
     */
    public function up(): void
    {
        // En PostgreSQL convertimos a VARCHAR con default; en MySQL modificamos el ENUM
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE chat_rooms ALTER COLUMN tipo TYPE VARCHAR(50);");
            DB::statement("ALTER TABLE chat_rooms ALTER COLUMN tipo SET DEFAULT 'general';");
        } else {
            DB::statement("ALTER TABLE chat_rooms MODIFY tipo ENUM('general','materia','beca_directa') DEFAULT 'general'");
        }

        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->foreignId('beca_id')
                ->nullable()
                ->after('activa')
                ->constrained('becas')
                ->nullOnDelete();

            // Propietario (estudiante que inicia el chat de beca)
            $table->foreignId('owner_id')
                ->nullable()
                ->after('beca_id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->dropForeign(['beca_id']);
            $table->dropForeign(['owner_id']);
            $table->dropColumn(['beca_id', 'owner_id']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE chat_rooms ALTER COLUMN tipo TYPE VARCHAR(50);");
            DB::statement("ALTER TABLE chat_rooms ALTER COLUMN tipo SET DEFAULT 'general';");
        } else {
            DB::statement("ALTER TABLE chat_rooms MODIFY tipo ENUM('general','materia') DEFAULT 'general'");
        }
    }
};