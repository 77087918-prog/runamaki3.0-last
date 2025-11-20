@extends('layouts.app')

@section('title', 'Gestión de Habilidades')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-red-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        🎯 Gestión de Habilidades
                    </h1>
                    <p class="text-purple-100 mt-2">Aprobar o rechazar habilidades enviadas por usuarios</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                    ← Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.habilidades') }}" 
                   class="px-4 py-2 rounded-lg {{ !request('estado') ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Todas
                </a>
                <a href="{{ route('admin.habilidades', ['estado' => 'pendiente']) }}" 
                   class="px-4 py-2 rounded-lg {{ request('estado') === 'pendiente' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Pendientes
                </a>
                <a href="{{ route('admin.habilidades', ['estado' => 'aprobado']) }}" 
                   class="px-4 py-2 rounded-lg {{ request('estado') === 'aprobado' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Aprobadas
                </a>
                <a href="{{ route('admin.habilidades', ['estado' => 'rechazado']) }}" 
                   class="px-4 py-2 rounded-lg {{ request('estado') === 'rechazado' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Rechazadas
                </a>
            </div>
        </div>

        <!-- Lista de Habilidades -->
        <div class="space-y-4">
            @forelse($habilidades as $habilidad)
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <!-- Vista Mobile -->
                    <div class="block lg:hidden">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-800 truncate pr-2">{{ $habilidad->titulo }}</h3>
                            <span class="px-2 py-1 rounded-full text-xs font-medium shrink-0
                                @if($habilidad->estado === 'pendiente') bg-orange-100 text-orange-800
                                @elseif($habilidad->estado === 'aprobado') bg-green-100 text-green-800
                                @elseif($habilidad->estado === 'rechazado') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($habilidad->estado) }}
                            </span>
                        </div>
                        
                        <!-- Descripción -->
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $habilidad->descripcion }}</p>
                        
                        <!-- Información en Grid -->
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <div class="bg-gray-50 p-2 rounded text-center">
                                <div class="text-sm font-bold text-gray-800">{{ $habilidad->usuario->name }}</div>
                                <div class="text-xs text-gray-500">Usuario</div>
                            </div>
                            <div class="bg-gray-50 p-2 rounded text-center">
                                <div class="text-sm font-bold text-indigo-600">{{ $habilidad->categoria->nombre }}</div>
                                <div class="text-xs text-gray-500">Categoría</div>
                            </div>
                            <div class="bg-gray-50 p-2 rounded text-center">
                                <div class="text-sm font-bold text-green-600">{{ $habilidad->puntos_sugeridos }} 🪙</div>
                                <div class="text-xs text-gray-500">Puntos</div>
                            </div>
                            <div class="bg-gray-50 p-2 rounded text-center">
                                <div class="text-sm font-bold text-blue-600">{{ $habilidad->horas_ofrecidas }}h</div>
                                <div class="text-xs text-gray-500">Horas</div>
                            </div>
                        </div>
                        
                        <div class="text-xs text-gray-500 mb-3">
                            Enviado {{ $habilidad->created_at->diffForHumans() }}
                        </div>
                        
                        @if($habilidad->motivo_rechazo)
                            <div class="mb-3 p-2 bg-red-50 border border-red-200 rounded">
                                <p class="text-xs text-red-800"><strong>Motivo:</strong> {{ $habilidad->motivo_rechazo }}</p>
                            </div>
                        @endif
                        
                        @if($habilidad->estado === 'pendiente')
                            <div class="flex gap-2 pt-3 border-t border-gray-100">
                                <form method="POST" action="{{ route('admin.habilidades.aprobar', $habilidad) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="w-full px-3 py-2 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition">
                                        ✅ Aprobar
                                    </button>
                                </form>
                                <button onclick="mostrarModalRechazo({{ $habilidad->id }})" 
                                        class="flex-1 px-3 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition">
                                    ❌ Rechazar
                                </button>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Vista Desktop -->
                    <div class="hidden lg:block">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <h3 class="text-xl font-bold text-gray-800">{{ $habilidad->titulo }}</h3>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        @if($habilidad->estado === 'pendiente') bg-orange-100 text-orange-800
                                        @elseif($habilidad->estado === 'aprobado') bg-green-100 text-green-800
                                        @elseif($habilidad->estado === 'rechazado') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($habilidad->estado) }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 mb-4">{{ $habilidad->descripcion }}</p>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                                        <div class="text-lg font-bold text-gray-800">{{ $habilidad->usuario->name }}</div>
                                        <div class="text-sm text-gray-500">Usuario</div>
                                    </div>
                                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                                        <div class="text-lg font-bold text-indigo-600">{{ $habilidad->categoria->nombre }}</div>
                                        <div class="text-sm text-gray-500">Categoría</div>
                                    </div>
                                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                                        <div class="text-lg font-bold text-green-600">{{ $habilidad->puntos_sugeridos }} 🪙</div>
                                        <div class="text-sm text-gray-500">Puntos</div>
                                    </div>
                                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                                        <div class="text-lg font-bold text-blue-600">{{ $habilidad->horas_ofrecidas }}h</div>
                                        <div class="text-sm text-gray-500">Horas</div>
                                    </div>
                                </div>
                                
                                <div class="text-sm text-gray-500">
                                    Enviado {{ $habilidad->created_at->diffForHumans() }}
                                </div>
                                
                                @if($habilidad->motivo_rechazo)
                                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-sm text-red-800"><strong>Motivo de rechazo:</strong> {{ $habilidad->motivo_rechazo }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            @if($habilidad->estado === 'pendiente')
                                <div class="flex flex-col gap-2 ml-6">
                                    <form method="POST" action="{{ route('admin.habilidades.aprobar', $habilidad) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                            ✅ Aprobar
                                        </button>
                                    </form>
                                    <button onclick="mostrarModalRechazo({{ $habilidad->id }})" 
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                        ❌ Rechazar
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <div class="text-6xl mb-4">🎯</div>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">No hay habilidades</h3>
                    <p class="text-gray-500">No se encontraron habilidades con los filtros aplicados</p>
                </div>
            @endforelse
        </div>
        
        <!-- Paginación -->
        @if($habilidades->hasPages())
            <div class="mt-8">
                {{ $habilidades->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal para rechazar habilidad -->
<div id="modalRechazo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-red-600 mb-4">❌ Rechazar Habilidad</h3>
        <form id="formRechazo" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Motivo del rechazo
                </label>
                <textarea name="motivo" 
                          class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                          rows="4" 
                          required
                          placeholder="Explica por qué se rechaza esta habilidad..."></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="submit" 
                        class="flex-1 bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                    Rechazar
                </button>
                <button type="button" 
                        onclick="cerrarModalRechazo()" 
                        class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function mostrarModalRechazo(habilidadId) {
    document.getElementById('modalRechazo').classList.remove('hidden');
    document.getElementById('formRechazo').action = `/admin/habilidades/${habilidadId}/rechazar`;
}

function cerrarModalRechazo() {
    document.getElementById('modalRechazo').classList.add('hidden');
    document.getElementById('formRechazo').reset();
}
</script>
@endsection