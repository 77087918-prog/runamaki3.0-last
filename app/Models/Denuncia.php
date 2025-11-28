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
        'descripcion',
        'estado',
        'fecha_resolucion',
        'admin_comentario',
        'comentario_admin',
        'procesada_por',
        'procesada_at',
    ];

    protected $casts = [
        'fecha_resolucion' => 'datetime',
        'procesada_at' => 'datetime',
    ];

    protected $appends = ['descripcion'];

    /**
     * Accessor para descripcion (alias de motivo)
     */
    public function getDescripcionAttribute()
    {
        return $this->motivo;
    }

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
     * Admin que procesó la denuncia
     */
    public function procesadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'procesada_por');
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
