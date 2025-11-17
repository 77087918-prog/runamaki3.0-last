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
        Schema::create('valoraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trueque_id')->constrained('trueques')->onDelete('cascade');
            $table->foreignId('evaluador_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evaluado_id')->constrained('users')->onDelete('cascade');
            $table->integer('puntuacion');
            $table->text('comentario')->nullable();
            $table->timestamps();
            
            $table->unique(['trueque_id', 'evaluador_id'], 'unique_valoracion');
            $table->index('evaluado_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valoraciones');
    }
};
