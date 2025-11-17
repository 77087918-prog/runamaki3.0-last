@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="card fade-in">
            <div class="flex items-start gap-6 mb-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                    {{ $usuario->iniciales }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl font-bold">{{ $usuario->name }}</h1>
                        <span class="px-3 py-1 rounded-full text-sm font-medium text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            {{ $usuario->nivel_emoji }} {{ $usuario->nivel }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4 mt-2">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= floor($usuario->reputacion) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                            <span class="text-sm text-gray-600 ml-2 font-medium">{{ number_format($usuario->reputacion, 1) }}</span>
                        </div>
                        <span class="text-sm text-gray-400">|</span>
                        <span class="text-sm text-gray-600 font-medium">{{ $usuario->puntos_runa }} Runas</span>
                        <span class="text-sm text-gray-400">|</span>
                        <span class="text-sm text-gray-500">Miembro desde {{ $usuario->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            @if($usuario->bio)
                <div class="mb-6">
                    <h2 class="font-semibold text-gray-900 mb-2">Biografía</h2>
                    <p class="text-gray-700">{{ $usuario->bio }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="stat-card">
                    <div class="stat-label">Habilidades</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $habilidades->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Trueques completados</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $truequesCompletados }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Reputación</div>
                    <div class="text-lg font-semibold text-gray-900">{{ number_format($usuario->reputacion, 2) }}/5.00</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $totalValoraciones }} valoración{{ $totalValoraciones === 1 ? '' : 'es' }}</div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="card-title mb-4">Habilidades de {{ $usuario->name }}</h2>
                @if($habilidades->isEmpty())
                    <p class="card-muted">Este usuario no tiene habilidades publicadas.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($habilidades as $habilidad)
                            <div class="border rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-gray-900">{{ $habilidad->titulo }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full" style="background-color: {{ $habilidad->categoria->color }}20; color: {{ $habilidad->categoria->color }};">
                                        {{ $habilidad->categoria->nombre }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($habilidad->descripcion, 100) }}</p>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-3 text-gray-500">
                                        <span>{{ $habilidad->horas_necesarias }}h</span>
                                        <span>{{ $habilidad->puntos_runa }} Runas</span>
                                    </div>
                                    <a href="{{ route('habilidades.show', $habilidad) }}" class="btn btn-primary text-xs">Ver detalle</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($usuario->logros->isNotEmpty())
            <div>
                <h2 class="card-title mb-4">Logros</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($usuario->logros as $logro)
                        <div class="text-center p-4 border rounded-lg">
                            <div class="text-4xl mb-2">{{ $logro->icono }}</div>
                            <div class="font-semibold text-sm text-gray-900">{{ $logro->nombre }}</div>
                            <div class="text-xs text-gray-600 mt-1">{{ $logro->descripcion }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Valoraciones recibidas -->
            <div class="mt-10">
                <h2 class="card-title mb-4">Valoraciones recibidas</h2>
                @if($valoracionesRecibidas->isEmpty())
                    <p class="card-muted">Este usuario aún no tiene valoraciones.</p>
                @else
                    <div class="space-y-4">
                        @foreach($valoracionesRecibidas as $valoracion)
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
                                @if($valoracion->trueque)
                                    <p class="text-xs text-gray-400 mt-2">Trueque #{{ $valoracion->trueque_id }} • {{ ucfirst($valoracion->trueque->estado) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
