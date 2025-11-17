<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denuncia extends Model
{
    use HasFactory;

    protected $fillable = [
        'denunciante_id',
        'denunciado_id',
        'tipo',
        'referencia_id',
        'motivo',
        'estado',
        'fecha_resolucion',
        'admin_comentario',
    ];

    protected $casts = [
        'fecha_resolucion' => 'datetime',
    ];

    /**
     * Usuario que hace la denuncia
     */
    public function denunciante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'denunciante_id');
    }

    /**
     * Usuario denunciado
     */
    public function denunciado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'denunciado_id');
    }

    /**
     * Scopes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnRevision($query)
    {
        return $query->where('estado', 'en_revision');
    }

    public function scopeResueltas($query)
    {
        return $query->where('estado', 'resuelto');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
