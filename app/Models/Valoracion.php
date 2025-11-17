<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Valoracion extends Model
{
    use HasFactory;

    protected $table = 'valoraciones';

    protected $fillable = [
        'trueque_id',
        'evaluador_id',
        'evaluado_id',
        'puntuacion',
        'comentario',
    ];

    protected $casts = [
        'puntuacion' => 'integer',
    ];

    /**
     * Trueque evaluado
     */
    public function trueque(): BelongsTo
    {
        return $this->belongsTo(Trueque::class);
    }

    /**
     * Usuario que hace la evaluación
     */
    public function evaluador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluador_id');
    }

    /**
     * Usuario que es evaluado
     */
    public function evaluado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluado_id');
    }

    /**
     * Scope para valoraciones positivas
     */
    public function scopePositivas($query)
    {
        return $query->where('puntuacion', '>=', 4);
    }

    /**
     * Scope para valoraciones negativas
     */
    public function scopeNegativas($query)
    {
        return $query->where('puntuacion', '<=', 2);
    }
}
