<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('habilidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->string('titulo', 150);
            $table->text('descripcion');
            $table->integer('horas_ofrecidas');
            $table->integer('puntos_sugeridos');
            $table->string('imagen')->nullable();
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('aprobado');
            $table->integer('visitas')->default(0);
            $table->timestamps();

            $table->index('estado');
        });
    }

    public function down()
    {
        Schema::dropIfExists('habilidades');
    }
};