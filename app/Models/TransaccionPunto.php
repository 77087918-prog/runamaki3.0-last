<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaccionPunto extends Model
{
    use HasFactory;

    protected $table = 'transacciones_puntos';

    protected $fillable = [
        'usuario_id',
        'tipo',
        'cantidad',
        'concepto',
        'trueque_id',
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    /**
     * Usuario de la transacción
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Trueque relacionado (opcional)
     */
    public function trueque(): BelongsTo
    {
        return $this->belongsTo(Trueque::class, 'trueque_id');
    }

    /**
     * Scopes
     */
    public function scopeGanados($query)
    {
        return $query->where('tipo', 'ganado');
    }

    public function scopeGastados($query)
    {
        return $query->where('tipo', 'gastado');
    }

    public function scopeAjustes($query)
    {
        return $query->where('tipo', 'ajuste_admin');
    }
}
