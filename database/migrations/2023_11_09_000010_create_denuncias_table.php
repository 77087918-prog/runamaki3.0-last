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
        Schema::create('denuncias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('denunciante_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('denunciado_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['usuario', 'habilidad', 'trueque']);
            $table->unsignedBigInteger('referencia_id');
            $table->text('motivo');
            $table->enum('estado', ['pendiente', 'en_revision', 'resuelto', 'rechazado'])->default('pendiente');
            $table->timestamp('fecha_resolucion')->nullable();
            $table->text('admin_comentario')->nullable();
            $table->timestamps();
            
            $table->index('estado');
            $table->index('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denuncias');
    }
};
