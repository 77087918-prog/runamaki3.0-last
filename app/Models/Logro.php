<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Logro extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'requisito_tipo',
        'requisito_valor',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'requisito_valor' => 'integer',
    ];

    /**
     * Usuarios que han obtenido este logro
     */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'usuarios_logros', 'logro_id', 'usuario_id')
            ->withTimestamps();
    }

    /**
     * Scope para logros activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope por tipo de requisito
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('requisito_tipo', $tipo);
    }
}
