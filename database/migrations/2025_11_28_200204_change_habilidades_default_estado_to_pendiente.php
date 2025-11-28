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
        // Change default value from 'aprobado' to 'pendiente'
        DB::statement("ALTER TABLE habilidades MODIFY COLUMN estado ENUM('pendiente', 'aprobado', 'rechazado') NOT NULL DEFAULT 'pendiente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback to original default 'aprobado'
        DB::statement("ALTER TABLE habilidades MODIFY COLUMN estado ENUM('pendiente', 'aprobado', 'rechazado') NOT NULL DEFAULT 'aprobado'");
    }
};
