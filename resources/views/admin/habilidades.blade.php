@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestión de Habilidades</h1>
                <p class="text-gray-600 mt-1">Aprueba o rechaza habilidades publicadas</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                ← Volver al Dashboard
            </a>
        </div>

        <!-- Filtros -->
        <div class="card mb-6">
            <form method="GET" action="{{ route('admin.habilidades') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" 
                           placeholder="Título de habilidad..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="aprobado" {{ request('estado') === 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                        Filtrar
                    </button>
                    <a href="{{ route('admin.habilidades') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="text-sm text-blue-600 font-medium">Total</div>
                <div class="text-2xl font-bold text-blue-700">{{ $habilidades->total() }}</div>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="text-sm text-yellow-600 font-medium">Pendientes</div>
                <div class="text-2xl font-bold text-yellow-700">{{ $habilidades->where('estado', 'pendiente')->count() }}</div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="text-sm text-green-600 font-medium">Aprobadas</div>
                <div class="text-2xl font-bold text-green-700">{{ $habilidades->where('estado', 'aprobado')->count() }}</div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="text-sm text-red-600 font-medium">Rechazadas</div>
                <div class="text-2xl font-bold text-red-700">{{ $habilidades->where('estado', 'rechazado')->count() }}</div>
            </div>
        </div>

        <!-- Lista de habilidades -->
        <div class="space-y-4">
            @forelse($habilidades as $habilidad)
                <div class="card">
                    <div class="flex items-start gap-4">
                        <!-- Imagen -->
                        <div class="flex-shrink-0">
                            @if($habilidad->imagen)
                                <img src="{{ Storage::url($habilidad->imagen) }}" alt="{{ $habilidad->titulo }}" class="w-24 h-24 object-cover rounded-lg">
                            @else
                                <div class="w-24 h-24 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Contenido -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $habilidad->titulo }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($habilidad->descripcion, 200) }}</p>
                                    
                                    <!-- Metadata -->
                                    <div class="flex flex-wrap items-center gap-4 mt-3 text-sm">
                                        <div class="flex items-center gap-1 text-gray-600">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            <a href="{{ route('perfil.show', $habilidad->usuario) }}" class="hover:text-purple-600">
                                                {{ $habilidad->usuario->name }}
                                            </a>
                                        </div>
                                        
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">
                                            {{ $habilidad->categoria->nombre }}
                                        </span>
                                        
                                        <div class="flex items-center gap-1 text-gray-500">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $habilidad->created_at->diffForHumans() }}
                                        </div>

                                        @if($habilidad->costo_runas)
                                            <div class="flex items-center gap-1 text-purple-600 font-semibold">
                                                💎 {{ $habilidad->costo_runas }} Runas
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Estado badge -->
                                <div class="ml-4">
                                    @if($habilidad->estado === 'pendiente')
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            ⏳ Pendiente
                                        </span>
                                    @elseif($habilidad->estado === 'aprobado')
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                            ✓ Aprobado
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                            ✕ Rechazado
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('habilidades.show', $habilidad) }}" target="_blank" class="btn btn-secondary text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Ver Detalles
                                </a>

                                @if($habilidad->estado === 'pendiente')
                                    <form action="{{ route('admin.habilidades.aprobar', $habilidad->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="btn bg-green-600 hover:bg-green-700 text-white text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Aprobar
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.habilidades.rechazar', $habilidad->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Rechazar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-sm text-gray-500">
                                        Estado actualizado el {{ $habilidad->updated_at->format('d/m/Y H:i') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card text-center py-12">
                    <div class="text-gray-400 text-5xl mb-4">📋</div>
                    <p class="text-gray-600">No se encontraron habilidades</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($habilidades->hasPages())
            <div class="mt-6">
                {{ $habilidades->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
