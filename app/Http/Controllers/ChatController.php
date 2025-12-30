<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\ChatMensaje;
use App\Models\User;

use App\Models\Trueque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Mostrar la página principal del chat
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener conversaciones únicas del usuario
        $conversations = ChatMensaje::where(function($query) use ($user) {
                $query->where('emisor_id', $user->id)
                      ->orWhere('receptor_id', $user->id);
            })
            ->with(['emisor', 'receptor'])
            ->latest()
            ->get()
            ->groupBy('conversacion_id')
            ->map(function($messages) {
                return $messages->first();
            })
            ->take(10);

        return view('chat.index', compact('conversations'));
    }

    /**
     * Mostrar una conversación específica
     */
    public function show($conversacionId)
    {
        $user = Auth::user();
        
        // Verificar acceso: puede ser por mensajes existentes o por trueque
        $hasAccess = false;
        
        // 1. Verificar si tiene mensajes en esta conversación
        $hasMessages = ChatMensaje::where('conversacion_id', $conversacionId)
            ->where(function($query) use ($user) {
                $query->where('emisor_id', $user->id)
                      ->orWhere('receptor_id', $user->id);
            })
            ->exists();
        
        if ($hasMessages) {
            $hasAccess = true;
        } else {
            // 2. Verificar si la conversacionId corresponde a un trueque del usuario
            // El conversacionId se genera como "conv_userId1_userId2" ordenado
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
                    
                    if ($truequeExists) {
                        $hasAccess = true;
                    }
                }
            }
        }
            
        if (!$hasAccess) {
            abort(403, 'No tienes acceso a esta conversación');
        }

        // Obtener mensajes de la conversación
        $messages = ChatMensaje::where('conversacion_id', $conversacionId)
            ->with(['emisor', 'receptor'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Obtener el otro usuario de la conversación
        $otherUser = null;
        if ($messages->isNotEmpty()) {
            $firstMessage = $messages->first();
            $otherUser = $firstMessage->emisor_id == $user->id 
                ? $firstMessage->receptor 
                : $firstMessage->emisor;
        } else {
            // Si no hay mensajes, obtener el otro usuario del conversacionId
            $parts = explode('_', $conversacionId);
            if (count($parts) === 3 && $parts[0] === 'conv') {
                $userId1 = (int)$parts[1];
                $userId2 = (int)$parts[2];
                $otherUserId = ($user->id === $userId1) ? $userId2 : $userId1;
                $otherUser = \App\Models\User::find($otherUserId);
            }
        }

        return view('chat.show', compact('messages', 'conversacionId', 'otherUser'));
    }

    /**
     * Enviar un mensaje
     */
    public function store(Request $request)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000',
            'receptor_id' => 'required|exists:users,id',
            'conversacion_id' => 'nullable|string'
        ]);

        $user = Auth::user();
        
        // Si no hay conversacion_id, crear uno nuevo
        $conversacionId = $request->conversacion_id ?: uniqid('conv_');

        $mensaje = ChatMensaje::create([
            'conversacion_id' => $conversacionId,
            'emisor_id' => $user->id,
            'receptor_id' => $request->receptor_id,
            'mensaje' => $request->mensaje,
        ]);

        $mensaje->load(['emisor', 'receptor']);

        // Emitir evento de WebSocket
        broadcast(new MessageSent($mensaje))->toOthers();

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $mensaje->id,
                'mensaje' => $mensaje->mensaje,
                'emisor_id' => $mensaje->emisor_id,
                'emisor_name' => $mensaje->emisor->name,
                'created_at' => $mensaje->created_at->toISOString(),
                'conversacion_id' => $conversacionId
            ]
        ]);
    }

    /**
     * Crear nueva conversación
     */
    public function crearConversacion(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = Auth::user();
        $recipient = User::where('email', $request->email)->first();

        if ($recipient->id === $user->id) {
            return response()->json([
                'error' => 'No puedes iniciar una conversación contigo mismo'
            ], 422);
        }

        // Verificar si ya existe una conversación entre estos usuarios
        $existingConversation = ChatMensaje::where(function($query) use ($user, $recipient) {
                $query->where('emisor_id', $user->id)->where('receptor_id', $recipient->id);
            })
            ->orWhere(function($query) use ($user, $recipient) {
                $query->where('emisor_id', $recipient->id)->where('receptor_id', $user->id);
            })
            ->first();

        if ($existingConversation) {
            return response()->json([
                'redirect' => route('chat.show', $existingConversation->conversacion_id)
            ]);
        }

        // Crear nueva conversación con mensaje inicial
        $conversacionId = ChatMensaje::generarConversacionId($user->id, $recipient->id);
        
        return response()->json([
            'success' => true,
            'conversacion_id' => $conversacionId,
            'recipient' => [
                'id' => $recipient->id,
                'name' => $recipient->name,
                'email' => $recipient->email
            ],
            'redirect' => route('chat.show', $conversacionId)
        ]);
    }

    /**
     * Buscar usuarios para iniciar chat
     */
    public function buscarUsuarios(Request $request)
    {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $usuarios = User::where('id', '!=', Auth::id())
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json($usuarios);
    }
}
