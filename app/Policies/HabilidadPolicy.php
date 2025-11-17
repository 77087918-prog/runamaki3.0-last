<?php

namespace App\Policies;

use App\Models\Habilidad;
use App\Models\User;

class HabilidadPolicy
{
    /**
     * Determine si el usuario puede ver cualquier habilidad
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine si el usuario puede ver la habilidad
     */
    public function view(User $user, Habilidad $habilidad): bool
    {
        return true; // Todas las habilidades aprobadas son públicas
    }

    /**
     * Determine si el usuario puede crear habilidades
     */
    public function create(User $user): bool
    {
        return $user->isActivo();
    }

    /**
     * Determine si el usuario puede actualizar la habilidad
     */
    public function update(User $user, Habilidad $habilidad): bool
    {
        return $user->id === $habilidad->usuario_id || $user->isAdmin();
    }

    /**
     * Determine si el usuario puede eliminar la habilidad
     */
    public function delete(User $user, Habilidad $habilidad): bool
    {
        return $user->id === $habilidad->usuario_id || $user->isAdmin();
    }

    /**
     * Determine si el usuario puede restaurar la habilidad
     */
    public function restore(User $user, Habilidad $habilidad): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine si el usuario puede eliminar permanentemente la habilidad
     */
    public function forceDelete(User $user, Habilidad $habilidad): bool
    {
        return $user->isAdmin();
    }
}