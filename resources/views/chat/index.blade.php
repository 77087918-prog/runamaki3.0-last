@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                
                <!-- Header -->
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">💬 {{ __('app.chat') }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('app.conversations') }}</p>
                    </div>
                    
                    <!-- Botón nuevo chat -->
                    <button 
                        onclick="openNewChatModal()" 
                        class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('app.new_message') }}
                    </button>
                </div>

                <!-- Lista de conversaciones -->
                @if($conversations->isEmpty())
                    <div class="text-center py-16">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.965 8.965 0 01-4.57-1.22L3 21l2.22-5.43A8.965 8.965 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ __('app.no_messages') }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">{{ __('app.start_conversation') }}</p>
                        <button 
                            onclick="openNewChatModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                            {{ __('app.start_conversation') }}
                        </button>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($conversations as $conversation)
                            @php
                                $otherUser = $conversation->emisor_id === Auth::id() 
                                    ? $conversation->receptor 
                                    : $conversation->emisor;
                            @endphp
                            <a 
                                href="{{ route('chat.show', $conversation->conversacion_id) }}" 
                                class="block bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-xl p-6 transition-all duration-200 hover:shadow-lg border border-gray-200 dark:border-gray-600">
                                
                                <div class="flex items-center space-x-4">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Información del chat -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $otherUser->name }}
                                            </h3>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $conversation->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 dark:text-gray-300 truncate mt-1">
                                            {{ Str::limit($conversation->mensaje, 50) }}
                                        </p>
                                        
                                        <div class="flex items-center mt-2">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $otherUser->email }}
                                            </span>
                                            
                                            <!-- Indicador en línea -->
                                            <div class="ml-auto flex items-center">
                                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                                <span class="ml-1 text-xs text-green-600 dark:text-green-400">{{ __('app.online') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para nuevo chat -->
<div id="newChatModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">💬 {{ __('app.new_message') }}</h3>
                <button 
                    onclick="closeNewChatModal()" 
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form onsubmit="createNewChat(event)" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('app.email') }}
                    </label>
                    <input 
                        type="email" 
                        id="recipientEmail" 
                        placeholder="ejemplo@correo.com"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                        required>
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button 
                        type="button" 
                        onclick="closeNewChatModal()" 
                        class="flex-1 px-4 py-3 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors duration-200 font-medium">
                        {{ __('app.cancel') }}
                    </button>
                    <button 
                        type="submit" 
                        id="createChatBtn"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-lg transition-all duration-200 font-medium transform hover:scale-105 disabled:opacity-50 disabled:transform-none">
                        {{ __('app.start_conversation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openNewChatModal() {
    document.getElementById('newChatModal').classList.remove('hidden');
    document.getElementById('recipientEmail').focus();
}

function closeNewChatModal() {
    document.getElementById('newChatModal').classList.add('hidden');
    document.getElementById('recipientEmail').value = '';
}

async function createNewChat(event) {
    event.preventDefault();
    
    const email = document.getElementById('recipientEmail').value;
    const btn = document.getElementById('createChatBtn');
    
    if (!email) return;
    
    // Deshabilitar botón
    btn.disabled = true;
    btn.textContent = 'Creando...';
    
    try {
        const response = await fetch('{{ route("chat.crear-conversacion") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email: email })
        });
        
        const data = await response.json();
        
        if (data.redirect) {
            window.location.href = data.redirect;
        } else if (data.success) {
            // Redirigir a nueva conversación
            window.location.href = `{{ url('/chat') }}/${data.conversacion_id}`;
        } else if (data.error) {
            alert(data.error);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al crear el chat');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Iniciar Chat';
    }
}

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeNewChatModal();
    }
});
</script>
@endsection