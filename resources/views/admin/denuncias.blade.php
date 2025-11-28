@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestión de Denuncias</h1>
                <p class="text-gray-600 mt-1">Revisa y procesa las denuncias de usuarios</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                ← Volver al Dashboard
            </a>
        </div>

        <!-- Filtros -->
        <div class="card mb-6">
            <form method="GET" action="{{ route('admin.denuncias') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="aprobada" {{ request('estado') === 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                        <option value="rechazada" {{ request('estado') === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                        Filtrar
                    </button>
                    <a href="{{ route('admin.denuncias') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="text-sm text-blue-600 font-medium">Total</div>
                <div class="text-2xl font-bold text-blue-700">{{ $denuncias->total() }}</div>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="text-sm text-yellow-600 font-medium">Pendientes</div>
                <div class="text-2xl font-bold text-yellow-700">{{ $denuncias->where('estado', 'pendiente')->count() }}</div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="text-sm text-green-600 font-medium">Aprobadas</div>
                <div class="text-2xl font-bold text-green-700">{{ $denuncias->where('estado', 'aprobada')->count() }}</div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="text-sm text-red-600 font-medium">Rechazadas</div>
                <div class="text-2xl font-bold text-red-700">{{ $denuncias->where('estado', 'rechazada')->count() }}</div>
            </div>
        </div>

        <!-- Lista de denuncias -->
        <div class="space-y-4">
            @forelse($denuncias as $denuncia)
                <div class="card">
                    <div class="flex items-start gap-4">
                        <!-- Icono de alerta -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full {{ $denuncia->estado === 'pendiente' ? 'bg-yellow-100' : ($denuncia->estado === 'aprobada' ? 'bg-red-100' : 'bg-gray-100') }} flex items-center justify-center">
                                <svg class="w-6 h-6 {{ $denuncia->estado === 'pendiente' ? 'text-yellow-600' : ($denuncia->estado === 'aprobada' ? 'text-red-600' : 'text-gray-600') }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <!-- Tipo de denuncia -->
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded">
                                            {{ ucfirst($denuncia->tipo) }}
                                        </span>
                                        
                                        @if($denuncia->estado === 'pendiente')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded">
                                                ⏳ Pendiente
                                            </span>
                                        @elseif($denuncia->estado === 'aprobada')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded">
                                                ✓ Aprobada
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded">
                                                ✕ Rechazada
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Descripción -->
                                    <p class="text-gray-900 mb-3">{{ $denuncia->descripcion }}</p>

                                    <!-- Información de usuarios -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">Denunciante:</p>
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ substr($denuncia->denunciante->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('perfil.show', $denuncia->denunciante) }}" class="text-sm font-medium text-blue-600 hover:underline">
                                                        {{ $denuncia->denunciante->name }}
                                                    </a>
                                                    <p class="text-xs text-gray-500">{{ $denuncia->denunciante->email }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">Denunciado:</p>
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ substr($denuncia->denunciado->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('perfil.show', $denuncia->denunciado) }}" class="text-sm font-medium text-red-600 hover:underline">
                                                        {{ $denuncia->denunciado->name }}
                                                    </a>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $denuncia->denunciado->email }}
                                                        @if($denuncia->denunciado->estado !== 'activo')
                                                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-700 rounded text-xs">
                                                                {{ ucfirst($denuncia->denunciado->estado) }}
                                                            </span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Metadata -->
                                    <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Creada: {{ $denuncia->created_at->format('d/m/Y H:i') }}
                                        </div>

                                        @if($denuncia->procesada_at)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Procesada: {{ $denuncia->procesada_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Comentario del admin -->
                                    @if($denuncia->comentario_admin)
                                        <div class="mt-3 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                                            <p class="text-xs text-purple-600 font-semibold mb-1">Comentario del administrador:</p>
                                            <p class="text-sm text-gray-700">{{ $denuncia->comentario_admin }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Acciones -->
                            @if($denuncia->estado === 'pendiente')
                                <div class="border-t border-gray-200 pt-4">
                                    <form action="{{ route('admin.denuncias.procesar', $denuncia->id) }}" method="POST" class="space-y-3" x-data="{ showComment: false }">
                                        @csrf
                                        
                                        <!-- Comentario opcional -->
                                        <div>
                                            <button type="button" @click="showComment = !showComment" class="text-sm text-purple-600 hover:text-purple-700 mb-2">
                                                <span x-show="!showComment">+ Agregar comentario</span>
                                                <span x-show="showComment">- Ocultar comentario</span>
                                            </button>
                                            <div x-show="showComment" x-transition>
                                                <textarea name="comentario_admin" rows="2" 
                                                          placeholder="Agregar un comentario sobre esta denuncia..." 
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500 text-sm"></textarea>
                                            </div>
                                        </div>

                                        <!-- Botones de acción -->
                                        <div class="flex items-center gap-3">
                                            <button type="submit" name="accion" value="aprobar" class="btn bg-green-600 hover:bg-green-700 text-white">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                Aprobar Denuncia
                                            </button>

                                            <button type="submit" name="accion" value="rechazar" class="btn bg-red-600 hover:bg-red-700 text-white">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                Rechazar Denuncia
                                            </button>

                                            <div class="text-sm text-gray-500">
                                                <p class="font-medium">Nota:</p>
                                                <p>Al aprobar, se tomará acción contra el denunciado ({{ $denuncia->denunciado->denuncias_recibidas ?? 0 }} denuncias previas)</p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="border-t border-gray-200 pt-3">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Estado:</span> 
                                        {{ $denuncia->estado === 'aprobada' ? 'Denuncia aprobada - Acción tomada contra el usuario' : 'Denuncia rechazada - No se tomó ninguna acción' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="card text-center py-12">
                    <div class="text-gray-400 text-5xl mb-4">✅</div>
                    <p class="text-gray-600">No hay denuncias</p>
                    <p class="text-sm text-gray-500 mt-2">¡Excelente! Todo en orden.</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($denuncias->hasPages())
            <div class="mt-6">
                {{ $denuncias->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Alpine.js para interactividad -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
