@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Imagen destacada -->
        <div class="relative h-72 md:h-96 bg-gradient-to-br from-purple-100 to-indigo-100 overflow-hidden">
            <img src="{{ $habilidad->imagen_url }}" 
                 alt="{{ $habilidad->titulo }}"
                 class="w-full h-full object-cover"
                 loading="lazy">
            <!-- Overlay gradient para mejor legibilidad -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
            
            <!-- Categoría badge -->
            <div class="absolute top-6 left-6">
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-white/95 backdrop-blur-sm shadow-lg" 
                                            style="color: {{ $habilidad->categoria->color ?? '#6366f1' }}">
                                        {{ $habilidad->categoria->nombre }}
                                </span>
            </div>
            
            <!-- Botones de acción -->
            @if($habilidad->usuario_id === auth()->id())
                <div class="absolute top-6 right-6 flex gap-3">
                    <a href="{{ route('habilidades.edit', $habilidad) }}" class="px-4 py-2 bg-white/95 backdrop-blur-sm text-gray-700 rounded-lg font-medium hover:bg-white transition shadow-lg">
                        Editar
                    </a>
                    <form action="{{ route('habilidades.destroy', $habilidad) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600/95 backdrop-blur-sm text-white rounded-lg font-medium hover:bg-red-700 transition shadow-lg" onclick="return confirm('¿Estás seguro de eliminar esta habilidad?')">
                            Eliminar
                        </button>
                    </form>
                </div>
            @endif
            
            <!-- Título superpuesto -->
            <div class="absolute bottom-6 left-6 right-6">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">{{ $habilidad->titulo }}</h1>
            </div>
        </div>

        <div class="p-8">
            <!-- Header -->
            <div class="mb-6">

        <!-- Descripción -->
        <div class="prose max-w-none mb-6">
            <p class="text-gray-700">{{ $habilidad->descripcion }}</p>
        </div>

        <!-- Detalles -->
        <div class="grid sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Horas ofrecidas</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $habilidad->horas_ofrecidas }}h</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Puntos sugeridos</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $habilidad->puntos_sugeridos }} <span class="text-base">Runas</span></p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Visitas</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $habilidad->visitas }}</p>
            </div>
        </div>

        <!-- Información del usuario -->
        <div class="border-t pt-6">
            <h3 class="font-medium text-gray-900 mb-3">Ofrecido por</h3>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ $habilidad->usuario->avatar_url }}" 
                         alt="{{ $habilidad->usuario->name }}"
                         class="h-12 w-12 rounded-full object-cover border-2 border-gray-200">
                    <div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('perfil.show', $habilidad->usuario) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                {{ $habilidad->usuario->name }}
                            </a>
                            <span class="text-lg">{{ $habilidad->usuario->nivel_emoji }}</span>
                            <span class="text-xs px-2 py-0.5 rounded bg-purple-100 text-purple-700 font-medium">
                                {{ $habilidad->usuario->nivel }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            @if($habilidad->usuario->ubicacion)
                                <span>📍 {{ $habilidad->usuario->ubicacion }}</span>
                                <span>•</span>
                            @endif
                            <span class="flex items-center gap-1">
                                ⭐ {{ number_format($habilidad->usuario->reputacion, 1) }}
                            </span>
                            <span>•</span>
                            <span>💎 {{ $habilidad->usuario->puntos_runa }} Runas</span>
                        </div>
                    </div>
                </div>

                @if($habilidad->usuario_id !== auth()->id() && $habilidad->estado === 'aprobado')
                    <a href="{{ route('trueques.create', $habilidad) }}" class="btn btn-primary">
                        🔄 Proponer Trueque
                    </a>
                @endif
            </div>
        </div>

        <!-- Estado de la habilidad -->
        @if($habilidad->estado !== 'aprobado')
            <div class="mt-4 p-4 rounded-lg {{ $habilidad->estado === 'pendiente' ? 'bg-yellow-50 text-yellow-800' : 'bg-red-50 text-red-800' }}">
                <p class="font-medium">
                    Estado: {{ ucfirst($habilidad->estado) }}
                </p>
            </div>
        @endif
    </div>

    <!-- Volver -->
    <div class="mt-6">
        <a href="{{ route('habilidades.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
            ← Volver a habilidades
        </a>
    </div>
</div>
@endsection
