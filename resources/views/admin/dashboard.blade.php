@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-red-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Admin -->
        <div class="bg-gradient-to-r from-red-600 to-orange-600 rounded-xl p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        🛡️ Panel Administrativo
                    </h1>
                    <p class="text-red-100 mt-2">Control total del sistema Runa Maki</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold">{{ auth()->user()->name }}</p>
                    <p class="text-red-200">Administrador</p>
                    <p class="text-sm text-red-100">{{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Usuarios -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Usuarios Total</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['usuarios_total'] }}</p>
                        <p class="text-xs text-green-500">
                            +{{ $stats['usuarios_nuevos_mes'] }} este mes
                        </p>
                    </div>
                    <div class="text-4xl text-blue-500">👥</div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Activos</span>
                        <span class="font-semibold">{{ $stats['usuarios_activos'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Habilidades -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Habilidades</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['habilidades_total'] }}</p>
                        <p class="text-xs text-orange-500">
                            {{ $stats['habilidades_pendientes'] }} pendientes
                        </p>
                    </div>
                    <div class="text-4xl text-purple-500">🎯</div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Aprobadas</span>
                        <span class="font-semibold">{{ $stats['habilidades_aprobadas'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Trueques -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Trueques</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['trueques_total'] }}</p>
                        <p class="text-xs text-blue-500">
                            {{ $stats['trueques_activos'] }} activos
                        </p>
                    </div>
                    <div class="text-4xl text-green-500">🔄</div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Completados</span>
                        <span class="font-semibold">{{ $stats['trueques_completados'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Denuncias -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Denuncias</p>
                        <p class="text-3xl font-bold text-red-600">{{ $stats['denuncias_pendientes'] }}</p>
                        <p class="text-xs text-gray-500">
                            Pendientes de revisión
                        </p>
                    </div>
                    <div class="text-4xl text-red-500">⚠️</div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Valoraciones</span>
                        <span class="font-semibold">{{ $stats['valoraciones_total'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegación Admin -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.usuarios') }}" 
               class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-center">
                    <div class="text-4xl mb-4">👥</div>
                    <h3 class="text-xl font-bold text-gray-800">Gestionar Usuarios</h3>
                    <p class="text-gray-600 text-sm mt-2">Administrar cuentas de usuario</p>
                </div>
            </a>

            <a href="{{ route('admin.habilidades') }}" 
               class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-center">
                    <div class="text-4xl mb-4">🎯</div>
                    <h3 class="text-xl font-bold text-gray-800">Revisar Habilidades</h3>
                    <p class="text-gray-600 text-sm mt-2">Aprobar o rechazar habilidades</p>
                </div>
            </a>

            <a href="{{ route('admin.denuncias') }}" 
               class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-center">
                    <div class="text-4xl mb-4">⚠️</div>
                    <h3 class="text-xl font-bold text-gray-800">Resolver Denuncias</h3>
                    <p class="text-gray-600 text-sm mt-2">Revisar reportes de usuarios</p>
                </div>
            </a>

            <a href="{{ route('admin.estadisticas') }}" 
               class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-center">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-bold text-gray-800">Ver Estadísticas</h3>
                    <p class="text-gray-600 text-sm mt-2">Analytics y métricas</p>
                </div>
            </a>
        </div>

        <!-- Contenido en dos columnas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Actividad Reciente -->
            <div class="lg:col-span-2">
                <!-- Usuarios Recientes -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        👤 Usuarios Recientes
                    </h3>
                    <div class="space-y-4">
                        @forelse($usuariosRecientes as $usuario)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $usuario->avatar_url }}" 
                                         alt="{{ $usuario->name }}" 
                                         class="w-10 h-10 rounded-full">
                                    <div>
                                        <p class="font-semibold">{{ $usuario->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $usuario->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($usuario->estado === 'activo') bg-green-100 text-green-800
                                        @elseif($usuario->estado === 'suspendido') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $usuario->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">No hay usuarios recientes</p>
                        @endforelse
                    </div>
                </div>

                <!-- Habilidades Pendientes -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        🔍 Habilidades Pendientes de Aprobación
                    </h3>
                    <div class="space-y-4">
                        @forelse($habilidadesPendientes as $habilidad)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-lg">{{ $habilidad->titulo }}</h4>
                                        <p class="text-gray-600">por {{ $habilidad->usuario->name }}</p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            📁 {{ $habilidad->categoria->nombre }}
                                        </p>
                                    </div>
                                    <div class="flex space-x-2 ml-4">
                                        <form method="POST" action="{{ route('admin.habilidades.aprobar', $habilidad) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600">
                                                ✅ Aprobar
                                            </button>
                                        </form>
                                        <button onclick="mostrarModalRechazo({{ $habilidad->id }})" 
                                                class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600">
                                            ❌ Rechazar
                                        </button>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm">{{ Str::limit($habilidad->descripcion, 100) }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    Enviado {{ $habilidad->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">No hay habilidades pendientes</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Puntos en Circulación -->
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-bold mb-2">💰 Economía Runa</h3>
                    <p class="text-3xl font-bold">{{ number_format($stats['puntos_circulacion']) }}</p>
                    <p class="text-sm opacity-90">Puntos Runa en circulación</p>
                </div>

                <!-- Denuncias Pendientes -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        🚨 Denuncias Pendientes
                    </h3>
                    <div class="space-y-3">
                        @forelse($denunciasPendientes as $denuncia)
                            <div class="border-l-4 border-red-500 pl-4 py-2">
                                <p class="font-semibold text-sm">{{ ucfirst(str_replace('_', ' ', $denuncia->tipo)) }}</p>
                                <p class="text-xs text-gray-600">
                                    {{ $denuncia->denunciante->name }} reportó a {{ $denuncia->denunciado->name }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $denuncia->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No hay denuncias pendientes</p>
                        @endforelse
                    </div>
                    @if($denunciasPendientes->count() > 0)
                        <a href="{{ route('admin.denuncias') }}" 
                           class="block text-center mt-4 bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                            Ver todas las denuncias
                        </a>
                    @endif
                </div>

                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">⚡ Acciones Rápidas</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.configuracion') }}" 
                           class="block w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 text-center">
                            ⚙️ Configuración
                        </a>
                        <a href="{{ route('admin.estadisticas') }}" 
                           class="block w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 text-center">
                            📈 Estadísticas Detalladas
                        </a>
                        <button onclick="window.location.reload()" 
                                class="block w-full bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 text-center">
                            🔄 Actualizar Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
                          rows="3" 
                          placeholder="Explica por qué se rechaza esta habilidad..."
                          required></textarea>
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