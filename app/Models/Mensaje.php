<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mensaje extends Model
{
    use HasFactory;

    protected $table = 'mensajes';

    protected $fillable = [
        'trueque_id',
        'remitente_id',
        'mensaje',
        'leido',
    ];

    protected $casts = [
        'leido' => 'boolean',
    ];

    /**
     * Trueque al que pertenece el mensaje
     */
    public function trueque(): BelongsTo
    {
        return $this->belongsTo(Trueque::class);
    }

    /**
     * Usuario remitente del mensaje
     */
    public function remitente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'remitente_id');
    }

    /**
     * Marcar mensaje como leído
     */
    public function marcarComoLeido(): void
    {
        $this->update(['leido' => true]);
    }

    /**
     * Scope para mensajes no leídos
     */
    public function scopeNoLeidos($query)
    {
        return $query->where('leido', false);
    }
}
