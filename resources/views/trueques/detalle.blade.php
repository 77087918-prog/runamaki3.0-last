@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Estado del trueque -->
    <div class="card">
        @php
            $estadoConfig = [
                'pendiente' => ['color' => 'yellow', 'texto' => 'Esperando respuesta'],
                'aceptado' => ['color' => 'blue', 'texto' => 'Aceptado - Coordinen para realizar el intercambio'],
                'completado' => ['color' => 'green', 'texto' => 'Trueque completado exitosamente'],
                'rechazado' => ['color' => 'red', 'texto' => 'Propuesta rechazada'],
                'cancelado' => ['color' => 'gray', 'texto' => 'Trueque cancelado'],
            ];
            $config = $estadoConfig[$trueque->estado];
        @endphp

        <div class="bg-{{ $config['color'] }}-50 border border-{{ $config['color'] }}-200 rounded-lg p-4">
            <div>
                <p class="font-medium text-{{ $config['color'] }}-900">{{ ucfirst($trueque->estado) }}</p>
                <p class="text-sm text-{{ $config['color'] }}-700">{{ $config['texto'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Detalles del intercambio -->
        <div class="card">
            <h3 class="card-title mb-4">Detalles del Intercambio</h3>
            
            <!-- Habilidad que se ofrece -->
            <div class="bg-indigo-50 rounded-lg p-4 mb-4">
                <p class="text-xs text-indigo-600 font-medium mb-1">{{ $trueque->usuarioOfrece->name }} ofrece:</p>
                <h4 class="font-bold text-gray-900">{{ $trueque->habilidadOfrece->titulo }}</h4>
                <p class="text-sm text-gray-600 mt-1">{{ $trueque->habilidadOfrece->descripcion }}</p>
                <div class="mt-2 text-sm text-gray-500">
                    <span>{{ $trueque->habilidadOfrece->horas_ofrecidas }}h</span>
                    <span class="mx-2">•</span>
                    <span>{{ $trueque->habilidadOfrece->categoria->nombre }}</span>
                </div>
            </div>

            <!-- Habilidad que se recibe -->
            <div class="bg-purple-50 rounded-lg p-4 mb-4">
                <p class="text-xs text-purple-600 font-medium mb-1">{{ $trueque->usuarioRecibe->name }} ofrece:</p>
                <h4 class="font-bold text-gray-900">{{ $trueque->habilidadRecibe->titulo }}</h4>
                <p class="text-sm text-gray-600 mt-1">{{ $trueque->habilidadRecibe->descripcion }}</p>
                <div class="mt-2 text-sm text-gray-500">
                    <span>{{ $trueque->habilidadRecibe->horas_ofrecidas }}h</span>
                    <span class="mx-2">•</span>
                    <span>{{ $trueque->habilidadRecibe->categoria->nombre }}</span>
                </div>
            </div>

            <!-- Info adicional -->
            <div class="border-t pt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Puntos del intercambio:</span>
                    <span class="font-medium text-indigo-600">{{ $trueque->puntos_intercambio }} Runas</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Creado:</span>
                    <span class="text-gray-900">{{ $trueque->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($trueque->fecha_aceptacion)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Aceptado:</span>
                        <span class="text-gray-900">{{ $trueque->fecha_aceptacion->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
                @if($trueque->fecha_completado)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Completado:</span>
                        <span class="text-gray-900">{{ $trueque->fecha_completado->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
            </div>

            <!-- Acciones según estado -->
            <div class="mt-6 space-y-2">
                @if($trueque->estado === 'pendiente' && $es_receptor)
                    <form action="{{ route('trueques.aceptar', $trueque) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-full">
                            Aceptar Trueque
                        </button>
                    </form>
                    <form action="{{ route('trueques.rechazar', $trueque) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-danger w-full">
                            Rechazar
                        </button>
                    </form>
                @endif

                @if($trueque->estado === 'aceptado')
                    <form action="{{ route('trueques.completar', $trueque) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-full">
                            Marcar como Completado
                        </button>
                    </form>
                @endif

                @if(in_array($trueque->estado, ['pendiente', 'aceptado']) && !$es_receptor)
                    <form action="{{ route('trueques.cancelar', $trueque) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary w-full">
                            Cancelar Trueque
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Chat -->
        <div class="card flex flex-col" style="max-height: 600px;">
            <h3 class="card-title mb-4">Mensajes</h3>
            
            <!-- Mensajes -->
            <div class="flex-1 overflow-y-auto space-y-3 mb-4" id="mensajes-container">
                @forelse($mensajes as $mensaje)
                    <div class="{{ $mensaje->remitente_id === auth()->id() ? 'text-right' : 'text-left' }}">
                        <div class="inline-block max-w-xs">
                            <p class="text-xs text-gray-500 mb-1">{{ $mensaje->remitente->name }}</p>
                            <div class="rounded-lg px-4 py-2 {{ $mensaje->remitente_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                                <p class="text-sm">{{ $mensaje->mensaje }}</p>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">{{ $mensaje->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-sm py-8">No hay mensajes aún</p>
                @endforelse
            </div>

            <!-- Formulario de mensaje -->
            @if(!in_array($trueque->estado, ['rechazado', 'cancelado']))
                <form action="{{ route('mensajes.store', $trueque) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" 
                           name="mensaje" 
                           placeholder="Escribe un mensaje..." 
                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           required>
                    <button type="submit" class="btn btn-primary">
                        Enviar
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Valoraciones -->
    <div class="card space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="card-title">Valoraciones</h3>
            @if($trueque->estado === 'completado' && !$ya_valoro)
                <span class="text-xs text-gray-500">Puedes valorar este trueque</span>
            @endif
        </div>

        @if($trueque->estado === 'completado' && !$ya_valoro)
            <div class="border rounded-lg p-4 bg-gray-50">
                <form action="{{ route('valoraciones.store', $trueque) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Puntuación</label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="puntuacion" value="{{ $i }}" class="sr-only peer" required>
                                    <span class="text-2xl opacity-30 peer-checked:opacity-100 hover:opacity-75 transition">⭐</span>
                                </label>
                            @endfor
                        </div>
                    </div>
                    <div>
                        <label for="comentario" class="block text-sm font-medium text-gray-700 mb-2">Comentario (opcional)</label>
                        <textarea id="comentario" name="comentario" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar Valoración</button>
                </form>
            </div>
        @endif

        <div class="space-y-4">
            @forelse($valoraciones as $valoracion)
                <div class="border rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-900">{{ $valoracion->evaluador->name }}</span>
                            <span class="text-xs text-gray-500">{{ $valoracion->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-lg {{ $i <= $valoracion->puntuacion ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    @if($valoracion->comentario)
                        <p class="text-sm text-gray-700">{{ $valoracion->comentario }}</p>
                    @else
                        <p class="text-sm text-gray-400 italic">Sin comentario</p>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">Aún no hay valoraciones para este trueque.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Auto-scroll al último mensaje
    const container = document.getElementById('mensajes-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endsection
