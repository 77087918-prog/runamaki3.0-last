@extends('layouts.app')

@section('title', 'Gestión de Denuncias')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-red-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-rose-600 rounded-xl p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        🚨 Gestión de Denuncias
                    </h1>
                    <p class="text-red-100 mt-2">Resolver reportes y denuncias de usuarios</p>
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
                <a href="{{ route('admin.denuncias') }}" 
                   class="px-4 py-2 rounded-lg {{ !request('estado') ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Todas
                </a>
                <a href="{{ route('admin.denuncias', ['estado' => 'pendiente']) }}" 
                   class="px-4 py-2 rounded-lg {{ request('estado') === 'pendiente' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Pendientes
                </a>
                <a href="{{ route('admin.denuncias', ['estado' => 'revisado']) }}" 
                   class="px-4 py-2 rounded-lg {{ request('estado') === 'revisado' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Revisadas
                </a>
                <a href="{{ route('admin.denuncias', ['estado' => 'resuelto']) }}" 
                   class="px-4 py-2 rounded-lg {{ request('estado') === 'resuelto' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Resueltas
                </a>
            </div>
        </div>

        <!-- Lista de Denuncias -->
        <div class="space-y-6">
            @forelse($denuncias as $denuncia)
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <!-- Vista Mobile -->
                    <div class="block lg:hidden">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-800">{{ $denuncia->tipo }}</h3>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($denuncia->estado === 'pendiente') bg-orange-100 text-orange-800
                                @elseif($denuncia->estado === 'revisado') bg-blue-100 text-blue-800
                                @elseif($denuncia->estado === 'resuelto') bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst($denuncia->estado) }}
                            </span>
                        </div>
                        
                        <div class="text-xs text-gray-500 mb-3">
                            {{ $denuncia->created_at->format('d/m/Y H:i') }}
                        </div>
                        
                        <!-- Usuarios Compactos -->
                        <div class="space-y-3 mb-4">
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-blue-800 text-sm">👤 Denunciante</h4>
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    <img src="{{ $denuncia->denunciante->avatar ?? '/images/avatar-default.png' }}" 
                                         alt="Avatar" 
                                         class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-sm">{{ $denuncia->denunciante->name }}</p>
                                        <p class="text-xs text-gray-600 truncate">{{ $denuncia->denunciante->email }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-red-50 p-3 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-red-800 text-sm">⚠️ Denunciado</h4>
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    <img src="{{ $denuncia->denunciado->avatar ?? '/images/avatar-default.png' }}" 
                                         alt="Avatar" 
                                         class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-sm">{{ $denuncia->denunciado->name }}</p>
                                        <p class="text-xs text-gray-600 truncate">{{ $denuncia->denunciado->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Descripción -->
                        <div class="bg-gray-50 p-3 rounded-lg mb-4">
                            <h4 class="font-bold text-gray-800 text-sm mb-2">📝 Descripción</h4>
                            <p class="text-gray-700 text-sm">{{ $denuncia->descripcion }}</p>
                        </div>
                        
                        @if($denuncia->trueque)
                            <div class="bg-yellow-50 p-3 rounded-lg mb-4">
                                <h4 class="font-bold text-yellow-800 text-sm mb-2">🤝 Trueque</h4>
                                <div class="text-xs text-gray-700">
                                    <div><strong>Habilidad:</strong> {{ $denuncia->trueque->habilidad_ofrecida->titulo ?? 'N/A' }}</div>
                                    <div><strong>Estado:</strong> {{ $denuncia->trueque->estado }}</div>
                                    <div><strong>Fecha:</strong> {{ $denuncia->trueque->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($denuncia->resolucion)
                            <div class="bg-green-50 p-3 rounded-lg mb-4">
                                <h4 class="font-bold text-green-800 text-sm mb-2">✅ Resolución</h4>
                                <p class="text-gray-700 text-sm">{{ $denuncia->resolucion }}</p>
                            </div>
                        @endif
                        
                        @if($denuncia->estado !== 'resuelto')
                            <div class="space-y-2 pt-3 border-t border-gray-200">
                                @if($denuncia->estado === 'pendiente')
                                    <form method="POST" action="{{ route('admin.denuncias.marcar-revisado', $denuncia) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-full px-3 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition">
                                            👁️ Marcar Revisado
                                        </button>
                                    </form>
                                @endif
                                <button onclick="mostrarModalResolucion({{ $denuncia->id }})" 
                                        class="w-full px-3 py-2 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition">
                                    ✅ Resolver
                                </button>
                                <button onclick="mostrarModalSuspension({{ $denuncia->denunciado->id }}, '{{ $denuncia->denunciado->name }}')" 
                                        class="w-full px-3 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition">
                                    🚫 Suspender Usuario
                                </button>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Vista Desktop -->
                    <div class="hidden lg:block">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <h3 class="text-xl font-bold text-gray-800">{{ $denuncia->tipo }}</h3>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        @if($denuncia->estado === 'pendiente') bg-orange-100 text-orange-800
                                        @elseif($denuncia->estado === 'revisado') bg-blue-100 text-blue-800
                                        @elseif($denuncia->estado === 'resuelto') bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($denuncia->estado) }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $denuncia->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Información del Denunciante -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-bold text-blue-800 mb-2">👤 Denunciante</h4>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $denuncia->denunciante->avatar ?? '/images/avatar-default.png' }}" 
                                         alt="Avatar" 
                                         class="w-10 h-10 rounded-full">
                                    <div>
                                        <p class="font-medium">{{ $denuncia->denunciante->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $denuncia->denunciante->email }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Información del Denunciado -->
                            <div class="bg-red-50 p-4 rounded-lg">
                                <h4 class="font-bold text-red-800 mb-2">⚠️ Denunciado</h4>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $denuncia->denunciado->avatar ?? '/images/avatar-default.png' }}" 
                                         alt="Avatar" 
                                         class="w-10 h-10 rounded-full">
                                    <div>
                                        <p class="font-medium">{{ $denuncia->denunciado->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $denuncia->denunciado->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    <!-- Descripción de la Denuncia -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h4 class="font-bold text-gray-800 mb-2">📝 Descripción</h4>
                        <p class="text-gray-700">{{ $denuncia->descripcion }}</p>
                    </div>
                    
                    <!-- Información del Trueque (si existe) -->
                    @if($denuncia->trueque)
                        <div class="bg-yellow-50 p-4 rounded-lg mb-6">
                            <h4 class="font-bold text-yellow-800 mb-2">🤝 Trueque Relacionado</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-lg font-bold">{{ $denuncia->trueque->habilidad_ofrecida->titulo ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-600">Habilidad Ofrecida</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold">{{ $denuncia->trueque->estado }}</div>
                                    <div class="text-sm text-gray-600">Estado</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold">{{ $denuncia->trueque->created_at->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-600">Fecha</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Resolución (si existe) -->
                    @if($denuncia->resolucion)
                        <div class="bg-green-50 p-4 rounded-lg mb-4">
                            <h4 class="font-bold text-green-800 mb-2">✅ Resolución</h4>
                            <p class="text-gray-700">{{ $denuncia->resolucion }}</p>
                        </div>
                    @endif
                    
                    <!-- Acciones -->
                    @if($denuncia->estado !== 'resuelto')
                        <div class="flex flex-wrap gap-2 pt-4 border-t">
                            @if($denuncia->estado === 'pendiente')
                                <form method="POST" action="{{ route('admin.denuncias.marcar-revisado', $denuncia) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                        👁️ Marcar como Revisado
                                    </button>
                                </form>
                            @endif
                            
                            <button onclick="mostrarModalResolucion({{ $denuncia->id }})" 
                                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                ✅ Resolver
                            </button>
                            
                            <button onclick="mostrarModalSuspension({{ $denuncia->denunciado->id }}, '{{ $denuncia->denunciado->name }}')" 
                                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                🚫 Suspender Usuario
                            </button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <div class="text-6xl mb-4">🚨</div>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">No hay denuncias</h3>
                    <p class="text-gray-500">No se encontraron denuncias con los filtros aplicados</p>
                </div>
            @endforelse
        </div>
        
        <!-- Paginación -->
        @if($denuncias->hasPages())
            <div class="mt-8">
                {{ $denuncias->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal para resolver denuncia -->
<div id="modalResolucion" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-green-600 mb-4">✅ Resolver Denuncia</h3>
        <form id="formResolucion" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción de la resolución
                </label>
                <textarea name="resolucion" 
                          class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                          rows="4" 
                          required
                          placeholder="Describe cómo se resolvió esta denuncia..."></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="submit" 
                        class="flex-1 bg-green-500 text-white py-2 rounded-lg hover:bg-green-600">
                    Resolver
                </button>
                <button type="button" 
                        onclick="cerrarModalResolucion()" 
                        class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para suspender usuario -->
<div id="modalSuspension" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-red-600 mb-4">🚫 Suspender Usuario</h3>
        <form id="formSuspension" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <p class="text-gray-700 mb-2">¿Estás seguro de que quieres suspender al usuario <strong id="nombreUsuario"></strong>?</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Motivo de la suspensión
                </label>
                <textarea name="motivo" 
                          class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                          rows="3" 
                          required
                          placeholder="Explica el motivo de la suspensión..."></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="submit" 
                        class="flex-1 bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                    Suspender
                </button>
                <button type="button" 
                        onclick="cerrarModalSuspension()" 
                        class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function mostrarModalResolucion(denunciaId) {
    document.getElementById('modalResolucion').classList.remove('hidden');
    document.getElementById('formResolucion').action = `/admin/denuncias/${denunciaId}/resolver`;
}

function cerrarModalResolucion() {
    document.getElementById('modalResolucion').classList.add('hidden');
    document.getElementById('formResolucion').reset();
}

function mostrarModalSuspension(usuarioId, nombreUsuario) {
    document.getElementById('modalSuspension').classList.remove('hidden');
    document.getElementById('formSuspension').action = `/admin/usuarios/${usuarioId}/suspender`;
    document.getElementById('nombreUsuario').textContent = nombreUsuario;
}

function cerrarModalSuspension() {
    document.getElementById('modalSuspension').classList.add('hidden');
    document.getElementById('formSuspension').reset();
}
</script>
@endsection