<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trueque extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_ofrece_id',
        'usuario_recibe_id',
        'habilidad_ofrece_id',
        'habilidad_recibe_id',
        'tipo_trueque',
        'puntos_intercambio',
        'estado',
        'fecha_aceptacion',
        'fecha_completado',
    ];

    protected $casts = [
        'fecha_aceptacion' => 'datetime',
        'fecha_completado' => 'datetime',
    ];

    /**
     * Usuario que ofrece la habilidad
     */
    public function usuarioOfrece(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_ofrece_id');
    }

    /**
     * Usuario que recibe la habilidad
     */
    public function usuarioRecibe(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_recibe_id');
    }

    /**
     * Habilidad que se ofrece (opcional para trueques por puntos)
     */
    public function habilidadOfrece(): BelongsTo
    {
        return $this->belongsTo(Habilidad::class, 'habilidad_ofrece_id');
    }

    /**
     * Habilidad que se recibe (opcional para trueques por puntos)
     */
    public function habilidadRecibe(): BelongsTo
    {
        return $this->belongsTo(Habilidad::class, 'habilidad_recibe_id');
    }

    /**
     * Verificar si es trueque habilidad por habilidad
     */
    public function esHabilidadPorHabilidad(): bool
    {
        return $this->tipo_trueque === 'habilidad_por_habilidad';
    }

    /**
     * Verificar si es trueque habilidad por puntos
     */
    public function esHabilidadPorPuntos(): bool
    {
        return $this->tipo_trueque === 'habilidad_por_puntos';
    }

    /**
     * Verificar si es trueque puntos por habilidad
     */
    public function esPuntosPorHabilidad(): bool
    {
        return $this->tipo_trueque === 'puntos_por_habilidad';
    }

    /**
     * Obtener descripción del tipo de trueque
     */
    public function getTipoTruequeDescripcion(): string
    {
        return match($this->tipo_trueque) {
            'habilidad_por_habilidad' => 'Intercambio de Habilidades',
            'habilidad_por_puntos' => 'Habilidad por Runas',
            'puntos_por_habilidad' => 'Runas por Habilidad',
            default => 'Tipo desconocido'
        };
    }

    /**
     * Mensajes del trueque
     */
    public function mensajes(): HasMany
    {
        return $this->hasMany(Mensaje::class);
    }

    /**
     * Valoraciones del trueque
     */
    public function valoraciones(): HasMany
    {
        return $this->hasMany(Valoracion::class);
    }

    /**
     * Transacciones de puntos relacionadas
     */
    public function transacciones(): HasMany
    {
        return $this->hasMany(TransaccionPunto::class);
    }

    /**
     * Scopes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAceptados($query)
    {
        return $query->where('estado', 'aceptado');
    }

    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }
}
