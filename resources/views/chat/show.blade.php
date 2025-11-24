@extends('layouts.app')

@section('content')
<div class="h-screen flex flex-col">
    <!-- Header del chat -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex-shrink-0">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Botón volver -->
                <a 
                    href="{{ route('chat.index') }}" 
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                
                @if($otherUser)
                    <!-- Avatar y info del usuario -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $otherUser->name }}</h2>
                            <p class="text-sm text-green-600 dark:text-green-400 flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                En línea
                            </p>
                        </div>
                    </div>
                @else
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Chat</h2>
                @endif
            </div>
            
            <!-- Indicador de conexión WebSocket -->
            <div id="connectionStatus" class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-gray-400 rounded-full" id="wsIndicator"></div>
                <span class="text-sm text-gray-500" id="wsStatus">Conectando...</span>
            </div>
        </div>
    </div>

    <!-- Área de mensajes -->
    <div class="flex-1 overflow-hidden bg-gray-50 dark:bg-gray-900">
        <div 
            id="messagesContainer" 
            class="h-full overflow-y-auto p-6 space-y-4"
            style="scroll-behavior: smooth;">
            
            @if($messages->isEmpty())
                <div class="text-center py-16">
                    <div class="mx-auto w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.965 8.965 0 01-4.57-1.22L3 21l2.22-5.43A8.965 8.965 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">¡Inicia la conversación!</h3>
                    <p class="text-gray-500 dark:text-gray-400">Envía un mensaje para comenzar a chatear en tiempo real</p>
                </div>
            @else
                @foreach($messages as $message)
                    <div class="flex {{ $message->emisor_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            @if($message->emisor_id !== Auth::id())
                                <!-- Mensaje recibido -->
                                <div class="flex items-end space-x-2">
                                    <div class="w-6 h-6 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($message->emisor->name, 0, 1)) }}
                                    </div>
                                    <div class="bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 rounded-2xl rounded-bl-md shadow-md">
                                        <p class="text-sm">{{ $message->mensaje }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $message->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <!-- Mensaje enviado -->
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-3 rounded-2xl rounded-br-md shadow-md">
                                    <p class="text-sm">{{ $message->mensaje }}</p>
                                    <p class="text-xs text-blue-100 mt-1 flex items-center justify-end">
                                        {{ $message->created_at->format('H:i') }}
                                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Formulario de envío -->
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-6 flex-shrink-0">
        <form id="messageForm" onsubmit="sendMessage(event)" class="flex space-x-4">
            @csrf
            <input type="hidden" id="conversacionId" value="{{ $conversacionId }}">
            @if($otherUser)
                <input type="hidden" id="receptorId" value="{{ $otherUser->id }}">
            @endif
            
            <div class="flex-1">
                <input 
                    type="text" 
                    id="messageInput" 
                    placeholder="Escribe tu mensaje..." 
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                    maxlength="1000"
                    required>
            </div>
            
            <button 
                type="submit" 
                id="sendButton"
                class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-semibold transform hover:scale-105 disabled:opacity-50 disabled:transform-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </button>
        </form>
        
        <!-- Indicador de escritura -->
        <div id="typingIndicator" class="hidden mt-3 text-sm text-gray-500 dark:text-gray-400 flex items-center">
            <div class="flex space-x-1 mr-2">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
            <span>Escribiendo...</span>
        </div>
    </div>
</div>

<script>
let lastMessageId = {{ $messages->isNotEmpty() ? $messages->last()->id : 0 }};
let isConnected = false;

// Función para actualizar el estado de conexión
function updateConnectionStatus(connected) {
    isConnected = connected;
    const indicator = document.getElementById('wsIndicator');
    const status = document.getElementById('wsStatus');
    
    if (connected) {
        indicator.className = 'w-3 h-3 bg-green-500 rounded-full animate-pulse';
        status.textContent = 'Conectado';
        status.className = 'text-sm text-green-600';
    } else {
        indicator.className = 'w-3 h-3 bg-red-500 rounded-full';
        status.textContent = 'Desconectado';
        status.className = 'text-sm text-red-600';
    }
}

// Configurar Laravel Echo para escuchar mensajes en tiempo real
const conversacionId = document.getElementById('conversacionId').value;

// Suscribirse al canal privado de la conversación
window.Echo.private(`chat.${conversacionId}`)
    .listen('.message.sent', (e) => {
        console.log('Mensaje recibido:', e);
        addMessageToChat(e, false);
        setTimeout(() => scrollToBottom(), 100); // Pequeño delay para asegurar el scroll
    })
    .error((error) => {
        console.error('Error en WebSocket:', error);
        updateConnectionStatus(false);
    });

// Verificar conexión
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('WebSocket conectado');
    updateConnectionStatus(true);
});

window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.log('WebSocket desconectado');
    updateConnectionStatus(false);
});

// Función para enviar mensaje
async function sendMessage(event) {
    event.preventDefault();
    
    const messageInput = document.getElementById('messageInput');
    const mensaje = messageInput.value.trim();
    const receptorId = document.getElementById('receptorId')?.value;
    const conversacionId = document.getElementById('conversacionId').value;
    
    if (!mensaje || !receptorId) return;
    
    // Deshabilitar entrada mientras se envía
    const sendButton = document.getElementById('sendButton');
    sendButton.disabled = true;
    messageInput.disabled = true;
    
    try {
        const response = await fetch('{{ route("chat.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                mensaje: mensaje,
                receptor_id: receptorId,
                conversacion_id: conversacionId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            messageInput.value = '';
            addMessageToChat(data.message, true);
            lastMessageId = data.message.id;
            setTimeout(() => scrollToBottom(), 100); // Pequeño delay para asegurar el scroll
        } else {
            throw new Error('Error al enviar mensaje');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al enviar mensaje. Por favor intenta de nuevo.');
    } finally {
        sendButton.disabled = false;
        messageInput.disabled = false;
        messageInput.focus();
    }
}

// Función para agregar mensaje al chat
function addMessageToChat(message, isOwn = false) {
    console.log('Agregando mensaje:', message, 'isOwn:', isOwn);
    const messagesContainer = document.getElementById('messagesContainer');
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${isOwn ? 'justify-end' : 'justify-start'}`;
    
    const now = new Date();
    const timeString = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
    
    if (isOwn) {
        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-3 rounded-2xl rounded-br-md shadow-md">
                    <p class="text-sm">${message.mensaje}</p>
                    <p class="text-xs text-blue-100 mt-1 flex items-center justify-end">
                        ${timeString}
                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </p>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md">
                <div class="flex items-end space-x-2">
                    <div class="w-6 h-6 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        ${message.emisor_name.charAt(0).toUpperCase()}
                    </div>
                    <div class="bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 rounded-2xl rounded-bl-md shadow-md">
                        <p class="text-sm">${message.mensaje}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            ${timeString}
                        </p>
                    </div>
                </div>
            </div>
        `;
    }
    
    messagesContainer.appendChild(messageDiv);
}

// Función para hacer scroll hacia abajo
function scrollToBottom() {
    const messagesContainer = document.getElementById('messagesContainer');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}

// Inicializar cuando la página carga
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    document.getElementById('messageInput').focus();
    
    // Inicializar estado de conexión
    updateConnectionStatus(false);
});

// Auto-focus en input cuando se presiona cualquier tecla
document.addEventListener('keydown', function(e) {
    const messageInput = document.getElementById('messageInput');
    if (e.target !== messageInput && !e.ctrlKey && !e.metaKey && e.key.length === 1) {
        messageInput.focus();
    }
});
</script>
@endsection