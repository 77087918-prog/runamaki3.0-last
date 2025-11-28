@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-3">
                <svg class="w-10 h-10 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path>
                </svg>
                Panel de Administración
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Vista general del sistema Runamaki</p>
        </div>

        <!-- Estadísticas principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Usuarios -->
            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Total Usuarios</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $stats['total_usuarios'] }}</h3>
                        <p class="text-blue-100 text-xs mt-2">
                            <span class="font-semibold">{{ $stats['usuarios_nuevos_mes'] }}</span> nuevos este mes
                        </p>
                    </div>
                    <svg class="w-12 h-12 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
            </div>

            <!-- Total Habilidades -->
            <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Total Habilidades</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $stats['total_habilidades'] }}</h3>
                        <p class="text-purple-100 text-xs mt-2">
                            <span class="font-semibold">{{ $stats['habilidades_pendientes'] }}</span> pendientes
                        </p>
                    </div>
                    <svg class="w-12 h-12 text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"></path>
                    </svg>
                </div>
            </div>

            <!-- Total Trueques -->
            <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Total Trueques</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $stats['total_trueques'] }}</h3>
                        <p class="text-green-100 text-xs mt-2">
                            <span class="font-semibold">{{ $stats['trueques_completados'] }}</span> completados
                        </p>
                    </div>
                    <svg class="w-12 h-12 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <!-- Denuncias Pendientes -->
            <div class="card bg-gradient-to-br from-red-500 to-red-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm">Denuncias</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $stats['total_denuncias'] }}</h3>
                        <p class="text-red-100 text-xs mt-2">
                            <span class="font-semibold">{{ $stats['denuncias_pendientes'] }}</span> pendientes
                        </p>
                    </div>
                    <svg class="w-12 h-12 text-red-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Gráficas de Estadísticas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Gráfica de Usuarios -->
            <div class="card">
                <h2 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Registro de Usuarios (Últimos 6 meses)</h2>
                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg" style="position: relative; height: 300px; width: 100%;">
                    <canvas id="usuariosChart" style="max-height: 100%;"></canvas>
                </div>
            </div>

            <!-- Gráfica de Trueques -->
            <div class="card">
                <h2 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Trueques Realizados (Últimos 6 meses)</h2>
                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg" style="position: relative; height: 300px; width: 100%;">
                    <canvas id="truequesChart" style="max-height: 100%;"></canvas>
                </div>
            </div>

            <!-- Gráfica de Distribución -->
            <div class="card">
                <h2 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Estado de Usuarios</h2>
                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg" style="position: relative; height: 300px; width: 100%;">
                    <canvas id="distribucionChart" style="max-height: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Sección de Exportaciones -->
        <div class="card mb-6">
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                Exportar Reportes
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Exportar Usuarios -->
                <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition">
                    <h3 class="font-semibold text-gray-900 mb-3">Usuarios</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.usuarios.exportar.csv') }}" 
                           class="flex-1 btn btn-sm btn-secondary text-xs">
                            📊 Excel
                        </a>
                        <a href="{{ route('admin.usuarios.exportar.pdf') }}" 
                           class="flex-1 btn btn-sm btn-secondary text-xs">
                            📄 PDF
                        </a>
                    </div>
                </div>

                <!-- Exportar Habilidades -->
                <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition">
                    <h3 class="font-semibold text-gray-900 mb-3">Habilidades</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.habilidades.exportar.csv') }}" 
                           class="flex-1 btn btn-sm btn-secondary text-xs">
                            📊 Excel
                        </a>
                        <a href="{{ route('admin.habilidades.exportar.pdf') }}" 
                           class="flex-1 btn btn-sm btn-secondary text-xs">
                            📄 PDF
                        </a>
                    </div>
                </div>

                <!-- Exportar Trueques -->
                <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition">
                    <h3 class="font-semibold text-gray-900 mb-3">Trueques</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.trueques.exportar.csv') }}" 
                           class="flex-1 btn btn-sm btn-secondary text-xs">
                            📊 Excel
                        </a>
                        <a href="{{ route('admin.trueques.exportar.pdf') }}" 
                           class="flex-1 btn btn-sm btn-secondary text-xs">
                            📄 PDF
                        </a>
                    </div>
                </div>

                <!-- Exportar Denuncias -->
                <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition">
                    <h3 class="font-semibold text-gray-900 mb-3">Denuncias</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.denuncias.exportar.csv') }}" 
                           class="flex-1 btn btn-sm btn-secondary text-xs">
                            📊 Excel
                        </a>
                        <a href="{{ route('admin.denuncias.exportar.pdf') }}" 
                           class="flex-1 btn btn-sm btn-secondary text-xs">
                            📄 PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido principal en dos columnas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna principal (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Habilidades Pendientes -->
                <div class="card">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold">Habilidades Pendientes de Aprobación</h2>
                        <a href="{{ route('admin.habilidades') }}" class="text-purple-600 hover:text-purple-700 text-sm">
                            Ver todas →
                        </a>
                    </div>

                    @if($habilidadesPendientes->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <p>No hay habilidades pendientes</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($habilidadesPendientes as $habilidad)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900">{{ $habilidad->titulo }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($habilidad->descripcion, 100) }}</p>
                                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $habilidad->usuario->name }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $habilidad->created_at->diffForHumans() }}
                                                </span>
                                                <span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded">
                                                    {{ $habilidad->categoria->nombre }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            <form action="{{ route('admin.habilidades.aprobar', $habilidad->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-icon bg-green-100 hover:bg-green-200 text-green-700" title="Aprobar">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.habilidades.rechazar', $habilidad->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-icon bg-red-100 hover:bg-red-200 text-red-700" title="Rechazar">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Actividad Reciente -->
                <div class="card">
                    <h2 class="text-lg font-bold mb-4">Actividad Reciente</h2>
                    <div class="space-y-2">
                        @forelse($actividadReciente as $actividad)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                        <span class="text-xs font-bold text-purple-700">{{ substr($actividad->usuario->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-900">{{ $actividad->usuario->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $actividad->concepto }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-semibold {{ $actividad->cantidad > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $actividad->cantidad > 0 ? '+' : '' }}{{ $actividad->cantidad }}
                                    </span>
                                    <p class="text-xs text-gray-400">{{ $actividad->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-4">No hay actividad reciente</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Columna lateral (1/3) -->
            <div class="space-y-6">
                <!-- Usuarios Recientes -->
                <div class="card">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold">Usuarios Recientes</h2>
                        <a href="{{ route('admin.usuarios') }}" class="text-purple-600 hover:text-purple-700 text-sm">
                            Ver todos →
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($usuariosRecientes as $usuario)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold">
                                    {{ substr($usuario->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $usuario->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $usuario->email }}</p>
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $usuario->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Denuncias Pendientes -->
                <div class="card">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold">Denuncias Pendientes</h2>
                        <a href="{{ route('admin.denuncias') }}" class="text-purple-600 hover:text-purple-700 text-sm">
                            Ver todas →
                        </a>
                    </div>
                    @if($denunciasPendientes->isEmpty())
                        <div class="text-center py-6 text-gray-500">
                            <p class="text-sm">No hay denuncias pendientes</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($denunciasPendientes as $denuncia)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-start justify-between mb-2">
                                        <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">{{ $denuncia->tipo }}</span>
                                        <span class="text-xs text-gray-400">{{ $denuncia->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-2">{{ Str::limit($denuncia->descripcion, 80) }}</p>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-600">De: <strong>{{ $denuncia->denunciante->name }}</strong></span>
                                        <a href="{{ route('admin.denuncias') }}" class="text-purple-600 hover:underline">Revisar →</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Acciones Rápidas -->
                <div class="card bg-gradient-to-br from-purple-50 to-blue-50">
                    <h2 class="text-lg font-bold mb-4">Acciones Rápidas</h2>
                    <div class="space-y-2">
                        <a href="{{ route('admin.usuarios') }}" class="block w-full btn btn-secondary text-left">
                            <span class="flex items-center justify-between">
                                Gestionar Usuarios
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <a href="{{ route('admin.habilidades') }}" class="block w-full btn btn-secondary text-left">
                            <span class="flex items-center justify-between">
                                Gestionar Habilidades
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <a href="{{ route('admin.denuncias') }}" class="block w-full btn btn-secondary text-left">
                            <span class="flex items-center justify-between">
                                Revisar Denuncias
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-icon {
    @apply p-2 rounded-lg transition-colors duration-200;
}
</style>

<!-- Marker para cargar Chart.js -->
<div data-admin-charts style="display: none;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('📊 Iniciando carga de gráficos...');
    
    // Esperar a que Chart.js esté disponible
    const waitForChart = setInterval(() => {
        if (typeof Chart !== 'undefined') {
            clearInterval(waitForChart);
            console.log('✅ Chart.js disponible, iniciando gráficos...');
            initCharts();
        }
    }, 100);
    
    // Timeout de 5 segundos
    setTimeout(() => {
        if (typeof Chart === 'undefined') {
            clearInterval(waitForChart);
            console.error('❌ Chart.js no se cargó después de 5 segundos');
        }
    }, 5000);
});

function initCharts() {
        return;
    }
    console.log('✅ Chart.js cargado correctamente');
    
    // Configuración común
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0,
                    color: '#6B7280'
                },
                grid: {
                    color: 'rgba(107, 114, 128, 0.1)'
                }
            },
            x: {
                ticks: {
                    color: '#6B7280'
                },
                grid: {
                    color: 'rgba(107, 114, 128, 0.1)'
                }
            }
        }
    };

    // Datos desde el backend
    const statsData = @json($statsGraficas);
    const distribucionData = @json($distribucionUsuarios);
    
    console.log('📈 Datos de estadísticas:', statsData);
    console.log('📊 Datos de distribución:', distribucionData);

    // Gráfica de Usuarios
    const usuariosCtx = document.getElementById('usuariosChart');
    if (usuariosCtx) {
        console.log('🎨 Creando gráfico de usuarios...');
        try {
            new Chart(usuariosCtx, {
                type: 'line',
                data: {
                    labels: statsData.meses,
                    datasets: [{
                        label: 'Nuevos Usuarios',
                        data: statsData.usuarios,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Usuarios: ' + context.parsed.y;
                                }
                            }
                        }
                    }
                }
            });
            console.log('✅ Gráfico de usuarios creado');
        } catch(e) {
            console.error('❌ Error creando gráfico de usuarios:', e);
        }
    } else {
        console.error('❌ Canvas usuariosChart no encontrado');
    }

    // Gráfica de Trueques
    const truequesCtx = document.getElementById('truequesChart');
    if (truequesCtx) {
        console.log('🎨 Creando gráfico de trueques...');
        try {
            new Chart(truequesCtx, {
                type: 'bar',
                data: {
                    labels: statsData.meses,
                    datasets: [{
                        label: 'Trueques',
                        data: statsData.trueques,
                        backgroundColor: 'rgba(147, 51, 234, 0.8)',
                        borderColor: 'rgb(147, 51, 234)',
                        borderWidth: 1,
                        borderRadius: 8,
                        hoverBackgroundColor: 'rgba(147, 51, 234, 1)'
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Trueques: ' + context.parsed.y;
                                }
                            }
                        }
                    }
                }
            });
            console.log('✅ Gráfico de trueques creado');
        } catch(e) {
            console.error('❌ Error creando gráfico de trueques:', e);
        }
    } else {
        console.error('❌ Canvas truequesChart no encontrado');
    }

    // Gráfica de Distribución de Usuarios (Dona)
    const distribucionCtx = document.getElementById('distribucionChart');
    if (distribucionCtx) {
        console.log('🎨 Creando gráfico de distribución...');
        try {
            new Chart(distribucionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Activos', 'Suspendidos', 'Baneados'],
                    datasets: [{
                    data: [
                        distribucionData.activos,
                        distribucionData.suspendidos,
                        distribucionData.baneados
                    ],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            },
                            color: '#6B7280'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
        console.log('✅ Gráfico de distribución creado');
        } catch(e) {
            console.error('❌ Error creando gráfico de distribución:', e);
        }
    } else {
        console.error('❌ Canvas distribucionChart no encontrado');
    }
    
    console.log('🎉 Proceso de carga de gráficos completado');
}
</script>
@endsection
