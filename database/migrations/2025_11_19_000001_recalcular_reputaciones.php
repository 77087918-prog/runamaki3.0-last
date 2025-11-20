<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;

return new class extends Migration
{
    /**
     * Recalcular todas las reputaciones basadas en valoraciones
     */
    public function up(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            $promedio = $user->valoracionesRecibidas()->avg('puntuacion');
            $nuevaReputacion = $promedio ? round($promedio, 2) : 0;
            
            $user->update(['reputacion' => $nuevaReputacion]);
        }
    }

    public function down(): void
    {
        // No es necesario revertir
    }
};