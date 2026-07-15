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
            // Eliminamos el renameColumn y dejamos solo el cambio de tipo
            $table->dateTime('vencimiento')->change();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('becas', function (Blueprint $table) {
            // Lo mismo aquí para cuando se haga un rollback
            $table->date('vencimiento')->change();
    });
    }
};