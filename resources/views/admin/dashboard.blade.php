@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-red-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Admin -->
        <div class="bg-gradient-to-r from-red-600 to-orange-600 rounded-xl p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        🛡️ {{ __('app.admin.dashboard.title') }}
                    </h1>
                    <p class="text-red-50 mt-2 font-medium">{{ __('app.admin.dashboard.description') }}</p>
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
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('app.admin.statistics.total_users') }}</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['usuarios_total'] }}</p>
                        <p class="text-xs text-green-500 dark:text-green-400">
                            +{{ $stats['usuarios_nuevos_mes'] }} {{ __('app.admin.statistics.new_this_month') }}
                        </p>
                    </div>
                    <div class="text-4xl text-blue-500">👥</div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Activos</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $stats['usuarios_activos'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Habilidades -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Habilidades</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['habilidades_total'] }}</p>
                        <p class="text-xs text-orange-500 dark:text-orange-400">
                            {{ $stats['habilidades_pendientes'] }} pendientes
                        </p>
                    </div>
                    <div class="text-4xl text-purple-500">🎯</div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Aprobadas</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $stats['habilidades_aprobadas'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Trueques -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Trueques</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['trueques_total'] }}</p>
                        <p class="text-xs text-blue-500 dark:text-blue-400">
                            {{ $stats['trueques_activos'] }} activos
                        </p>
                    </div>
                    <div class="text-4xl text-green-500">🔄</div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Completados</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $stats['trueques_completados'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Denuncias -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Denuncias</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['denuncias_pendientes'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Pendientes de revisión
                        </p>
                    </div>
                    <div class="text-4xl text-red-500">⚠️</div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Valoraciones</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $stats['valoraciones_total'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegación Admin -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <a href="{{ route('admin.usuarios') }}" 
               class="group bg-white dark:bg-gray-800 rounded-xl p-4 sm:p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:bg-gradient-to-br hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20 border border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform">👥</div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-200 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">Gestionar Usuarios</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm mt-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 font-medium">Administrar cuentas de usuario</p>
                    <div class="mt-3 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-xs font-medium inline-block">
                        {{ $stats['usuarios_total'] }} usuarios
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.habilidades') }}" 
               class="group bg-white dark:bg-gray-800 rounded-xl p-4 sm:p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:bg-gradient-to-br hover:from-purple-50 hover:to-pink-50 dark:hover:from-purple-900/20 dark:hover:to-pink-900/20 border border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform">🎯</div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-200 group-hover:text-purple-700 dark:group-hover:text-purple-400 transition-colors">Revisar Habilidades</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm mt-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 font-medium">Aprobar o rechazar habilidades</p>
                    <div class="mt-3 px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 rounded-full text-xs font-medium inline-block">
                        {{ $stats['habilidades_pendientes'] }} pendientes
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.denuncias') }}" 
               class="group bg-white dark:bg-gray-800 rounded-xl p-4 sm:p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:bg-gradient-to-br hover:from-red-50 hover:to-rose-50 dark:hover:from-red-900/20 dark:hover:to-rose-900/20 border border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform">⚠️</div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-200 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">Resolver Denuncias</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm mt-2 group-hover:text-red-600 dark:group-hover:text-red-400 font-medium">Revisar reportes de usuarios</p>
                    <div class="mt-3 px-3 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-full text-xs font-medium inline-block">
                        {{ $stats['denuncias_pendientes'] }} pendientes
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.estadisticas') }}" 
               class="group bg-white dark:bg-gray-800 rounded-xl p-4 sm:p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:bg-gradient-to-br hover:from-green-50 hover:to-emerald-50 dark:hover:from-green-900/20 dark:hover:to-emerald-900/20 border border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform">📊</div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-200 group-hover:text-green-700 dark:group-hover:text-green-400 transition-colors">Ver Estadísticas</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm mt-2 group-hover:text-green-600 dark:group-hover:text-green-400 font-medium">Analytics y métricas</p>
                    <div class="mt-3 px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-xs font-medium inline-block">
                        Análisis completo
                    </div>
                </div>
            </a>
        </div>

        <!-- Contenido en dos columnas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Actividad Reciente -->
            <div class="lg:col-span-2">
                <!-- Usuarios Recientes -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                        👤 Usuarios Recientes
                    </h3>
                    <div class="space-y-4">
                        @forelse($usuariosRecientes as $usuario)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $usuario->avatar_url }}" 
                                         alt="{{ $usuario->name }}" 
                                         class="w-10 h-10 rounded-full">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $usuario->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $usuario->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($usuario->estado === 'activo') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200
                                        @elseif($usuario->estado === 'suspendido') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $usuario->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No hay usuarios recientes</p>
                        @endforelse
                    </div>
                </div>

                <!-- Habilidades Pendientes -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                        🔍 Habilidades Pendientes de Aprobación
                    </h3>
                    <div class="space-y-4">
                        @forelse($habilidadesPendientes as $habilidad)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ $habilidad->titulo }}</h4>
                                        <p class="text-gray-600 dark:text-gray-400">por {{ $habilidad->usuario->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            📁 {{ $habilidad->categoria->nombre }}
                                        </p>
                                    </div>
                                    <div class="flex space-x-2 ml-4">
                                        <form method="POST" action="{{ route('admin.habilidades.aprobar', $habilidad) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                                                ✅ Aprobar
                                            </button>
                                        </form>
                                        <button onclick="mostrarModalRechazo({{ $habilidad->id }})" 
                                                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                                            ❌ Rechazar
                                        </button>
                                    </div>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 text-sm">{{ Str::limit($habilidad->descripcion, 100) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    Enviado {{ $habilidad->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No hay habilidades pendientes</p>
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
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                        🚨 Denuncias Pendientes
                    </h3>
                    <div class="space-y-3">
                        @forelse($denunciasPendientes as $denuncia)
                            <div class="border-l-4 border-red-500 pl-4 py-2">
                                <p class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ ucfirst(str_replace('_', ' ', $denuncia->tipo)) }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $denuncia->denunciante->name }} reportó a {{ $denuncia->denunciado->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $denuncia->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">No hay denuncias pendientes</p>
                        @endforelse
                    </div>
                    @if($denunciasPendientes->count() > 0)
                        <a href="{{ route('admin.denuncias') }}" 
                           class="block text-center mt-4 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition">
                            Ver todas las denuncias
                        </a>
                    @endif
                </div>


            </div>
        </div>
    </div>
</div>

<!-- Modal para rechazar habilidad -->
<div id="modalRechazo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4 border border-gray-200 dark:border-gray-700">
        <h3 class="text-xl font-bold text-red-600 dark:text-red-400 mb-4">❌ Rechazar Habilidad</h3>
        <form id="formRechazo" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Motivo del rechazo
                </label>
                <textarea name="motivo" 
                          class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                          rows="3" 
                          placeholder="Explica por qué se rechaza esta habilidad..."
                          required></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="submit" 
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition">
                    Rechazar
                </button>
                <button type="button" 
                        onclick="cerrarModalRechazo()" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg font-medium transition">
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