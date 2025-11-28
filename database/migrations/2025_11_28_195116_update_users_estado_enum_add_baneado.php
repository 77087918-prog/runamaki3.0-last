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
        // MySQL no permite modificar ENUM directamente, hay que usar raw SQL
        DB::statement("ALTER TABLE users MODIFY COLUMN estado ENUM('activo', 'suspendido', 'inactivo', 'baneado') DEFAULT 'activo'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir usuarios baneados a suspendidos antes de eliminar la opción
        DB::statement("UPDATE users SET estado = 'suspendido' WHERE estado = 'baneado'");
        DB::statement("ALTER TABLE users MODIFY COLUMN estado ENUM('activo', 'suspendido', 'inactivo') DEFAULT 'activo'");
    }
};
