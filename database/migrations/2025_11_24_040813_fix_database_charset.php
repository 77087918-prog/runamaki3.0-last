<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convertir la base de datos completa a utf8mb4
        DB::statement('ALTER DATABASE `' . config('database.connections.mysql.database') . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        
        // Lista de tablas que necesitan conversión
        $tables = [
            'users',
            'habilidades',
            'trueques',
            'categorias',
            'mensajes',
            'valoraciones',
            'logros',
            'denuncias',
            'chat_mensajes',
            'configuracion'
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                // Convertir tabla completa
                DB::statement("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                // Convertir columnas específicas de texto
                $this->convertTextColumns($table);
            }
        }
    }

    /**
     * Convertir columnas de texto específicas
     */
    private function convertTextColumns($table)
    {
        $textColumns = [];
        
        switch ($table) {
            case 'users':
                $textColumns = ['name', 'email', 'ubicacion'];
                break;
            case 'habilidades':
                $textColumns = ['titulo', 'descripcion', 'ubicacion'];
                break;
            case 'categorias':
                $textColumns = ['nombre', 'descripcion', 'icono'];
                break;
            case 'mensajes':
                $textColumns = ['mensaje'];
                break;
            case 'chat_mensajes':
                $textColumns = ['mensaje'];
                break;
            case 'valoraciones':
                $textColumns = ['comentario'];
                break;
            case 'logros':
                $textColumns = ['nombre', 'descripcion'];
                break;
            case 'denuncias':
                $textColumns = ['razon', 'descripcion'];
                break;
        }
        
        foreach ($textColumns as $column) {
            try {
                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            } catch (Exception $e) {
                // Ignorar errores si la columna no existe
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revertir para evitar pérdida de datos
    }
};
