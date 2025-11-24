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
        'emisor_id',
        'receptor_id',
        'conversacion_id',
        'mensaje',
        'leido',
    ];

    protected $casts = [
        'leido' => 'boolean',
    ];

    /**
     * Usuario que envía el mensaje
     */
    public function emisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'emisor_id');
    }

    /**
     * Usuario que recibe el mensaje
     */
    public function receptor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receptor_id');
    }

    /**
     * Scope para mensajes de una conversación
     */
    public function scopeDeConversacion($query, $conversacionId)
    {
        return $query->where('conversacion_id', $conversacionId);
    }

    /**
     * Scope para mensajes no leídos
     */
    public function scopeNoLeidos($query)
    {
        return $query->where('leido', false);
    }

    /**
     * Scope para mensajes entre dos usuarios
     */
    public function scopeEntreUsuarios($query, $usuario1Id, $usuario2Id)
    {
        return $query->where(function ($q) use ($usuario1Id, $usuario2Id) {
            $q->where(function ($subQ) use ($usuario1Id, $usuario2Id) {
                $subQ->where('emisor_id', $usuario1Id)
                     ->where('receptor_id', $usuario2Id);
            })->orWhere(function ($subQ) use ($usuario1Id, $usuario2Id) {
                $subQ->where('emisor_id', $usuario2Id)
                     ->where('receptor_id', $usuario1Id);
            });
        });
    }

    /**
     * Marcar mensaje como leído
     */
    public function marcarComoLeido()
    {
        $this->update(['leido' => true]);
    }

    /**
     * Generar ID de conversación entre dos usuarios
     */
    public static function generarConversacionId($usuario1Id, $usuario2Id)
    {
        // Ordenar IDs para que la conversación siempre tenga el mismo ID
        $ids = [$usuario1Id, $usuario2Id];
        sort($ids);
        return 'conv_' . implode('_', $ids);
    }
}
