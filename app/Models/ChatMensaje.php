<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMensaje extends Model
{
    use HasFactory;

    protected $table = 'chat_mensajes';

    protected $fillable = [
        'usuario_id',
        'mensaje',
        'es_usuario',
    ];

    protected $casts = [
        'es_usuario' => 'boolean',
    ];

    /**
     * Usuario del chat
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para mensajes del usuario
     */
    public function scopeDelUsuario($query)
    {
        return $query->where('es_usuario', true);
    }

    /**
     * Scope para respuestas de la IA
     */
    public function scopeDeAsistente($query)
    {
        return $query->where('es_usuario', false);
    }
}
