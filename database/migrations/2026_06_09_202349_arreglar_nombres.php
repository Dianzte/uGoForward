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
        Schema::table('universidades', function (Blueprint $table) {
            $table->renameColumn('institucion', 'siglas');
            $table->string('nombre_completo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('universidades', function (Blueprint $table) {
            //
        });
    }
};
