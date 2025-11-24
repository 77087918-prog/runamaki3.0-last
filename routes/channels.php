<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal privado para chat entre usuarios
Broadcast::channel('chat.{conversacionId}', function ($user, $conversacionId) {
    // 1. Verificar si tiene mensajes en esta conversación
    $hasMessages = \App\Models\ChatMensaje::where('conversacion_id', $conversacionId)
        ->where(function ($query) use ($user) {
            $query->where('emisor_id', $user->id)
                  ->orWhere('receptor_id', $user->id);
        })->exists();
    
    if ($hasMessages) {
        return true;
    }
    
    // 2. Verificar acceso basado en trueque si no hay mensajes
    $parts = explode('_', $conversacionId);
    if (count($parts) === 3 && $parts[0] === 'conv') {
        $userId1 = (int)$parts[1];
        $userId2 = (int)$parts[2];
        
        // Verificar si el usuario actual es uno de los dos
        if ($user->id === $userId1 || $user->id === $userId2) {
            // Verificar si existe un trueque entre estos usuarios
            $truequeExists = \App\Models\Trueque::where(function($query) use ($userId1, $userId2) {
                $query->where('usuario_ofrece_id', $userId1)->where('usuario_recibe_id', $userId2);
            })->orWhere(function($query) use ($userId1, $userId2) {
                $query->where('usuario_ofrece_id', $userId2)->where('usuario_recibe_id', $userId1);
            })->exists();
            
            return $truequeExists;
        }
    }
    
    return false;
});