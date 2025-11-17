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
        Schema::create('trueques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_ofrece_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('usuario_recibe_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('habilidad_ofrece_id')->constrained('habilidades')->onDelete('cascade');
            $table->foreignId('habilidad_recibe_id')->constrained('habilidades')->onDelete('cascade');
            $table->integer('puntos_intercambio');
            $table->enum('estado', ['pendiente', 'aceptado', 'completado', 'rechazado', 'cancelado'])->default('pendiente');
            $table->timestamp('fecha_aceptacion')->nullable();
            $table->timestamp('fecha_completado')->nullable();
            $table->timestamps();
            
            $table->index('usuario_ofrece_id');
            $table->index('usuario_recibe_id');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trueques');
    }
};
