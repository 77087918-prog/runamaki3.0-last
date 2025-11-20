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
        Schema::table('trueques', function (Blueprint $table) {
            // Agregar tipo de trueque: habilidad_por_habilidad, habilidad_por_puntos, puntos_por_habilidad
            $table->enum('tipo_trueque', ['habilidad_por_habilidad', 'habilidad_por_puntos', 'puntos_por_habilidad'])
                  ->default('habilidad_por_habilidad')
                  ->after('habilidad_recibe_id');
            
            // Hacer habilidad_recibe_id opcional para trueques por puntos
            $table->unsignedBigInteger('habilidad_recibe_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trueques', function (Blueprint $table) {
            $table->dropColumn('tipo_trueque');
            // No podemos deshacer el cambio nullable fácilmente
        });
    }
};
