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
        Schema::table('chat_mensajes', function (Blueprint $table) {
            // Eliminar las columnas antiguas
            $table->dropForeign(['usuario_id']);
            $table->dropIndex(['usuario_id', 'created_at']);
            $table->dropColumn(['usuario_id', 'es_usuario']);
            
            // Agregar las nuevas columnas para chat entre usuarios
            $table->foreignId('emisor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receptor_id')->constrained('users')->onDelete('cascade');
            $table->string('conversacion_id')->index();
            $table->boolean('leido')->default(false);
            
            // Índices para optimización
            $table->index(['conversacion_id', 'created_at']);
            $table->index(['receptor_id', 'leido']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_mensajes', function (Blueprint $table) {
            // Revertir cambios
            $table->dropForeign(['emisor_id']);
            $table->dropForeign(['receptor_id']);
            $table->dropIndex(['conversacion_id', 'created_at']);
            $table->dropIndex(['receptor_id', 'leido']);
            $table->dropColumn(['emisor_id', 'receptor_id', 'conversacion_id', 'leido']);
            
            // Restaurar columnas originales
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->boolean('es_usuario')->default(true);
            $table->index(['usuario_id', 'created_at']);
        });
    }
};
