@extends('layouts.app')

@section('title', 'Acciones Rápidas')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-red-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        ⚡ Acciones Rápidas
                    </h1>
                    <p class="text-indigo-100 mt-2">Herramientas administrativas para gestión eficiente</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                    ← Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Acciones de Usuario -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Suspender Usuario -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-red-600 mb-4 flex items-center">
                    🚫 Suspender Usuario
                </h3>
                <form method="POST" action="{{ route('admin.acciones-rapidas.suspender-usuario') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email del usuario
                        </label>
                        <input type="email" 
                               name="email" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="usuario@ejemplo.com"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo
                        </label>
                        <textarea name="motivo" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                                  rows="3" 
                                  placeholder="Motivo de la suspensión..."
                                  required></textarea>
                    </div>
                    <button type="submit" 
                            class="w-full bg-red-500 text-white py-3 rounded-lg hover:bg-red-600 transition">
                        🚫 Suspender Usuario
                    </button>
                </form>
            </div>

            <!-- Reactivar Usuario -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-green-600 mb-4 flex items-center">
                    ✅ Reactivar Usuario
                </h3>
                <form method="POST" action="{{ route('admin.acciones-rapidas.reactivar-usuario') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email del usuario
                        </label>
                        <input type="email" 
                               name="email" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="usuario@ejemplo.com"
                               required>
                    </div>
                    <button type="submit" 
                            class="w-full bg-green-500 text-white py-3 rounded-lg hover:bg-green-600 transition">
                        ✅ Reactivar Usuario
                    </button>
                </form>
            </div>
        </div>

        <!-- Acciones de Puntos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Otorgar Puntos -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-yellow-600 mb-4 flex items-center">
                    🪙 Otorgar Puntos Runa
                </h3>
                <form method="POST" action="{{ route('admin.acciones-rapidas.otorgar-puntos') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email del usuario
                        </label>
                        <input type="email" 
                               name="email" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="usuario@ejemplo.com"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cantidad de puntos
                        </label>
                        <input type="number" 
                               name="puntos" 
                               min="1"
                               max="10000"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="100"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo
                        </label>
                        <input type="text" 
                               name="motivo" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="Bonificación por participación"
                               required>
                    </div>
                    <button type="submit" 
                            class="w-full bg-yellow-500 text-white py-3 rounded-lg hover:bg-yellow-600 transition">
                        🪙 Otorgar Puntos
                    </button>
                </form>
            </div>

            <!-- Descontar Puntos -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-orange-600 mb-4 flex items-center">
                    ⚠️ Descontar Puntos Runa
                </h3>
                <form method="POST" action="{{ route('admin.acciones-rapidas.descontar-puntos') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email del usuario
                        </label>
                        <input type="email" 
                               name="email" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="usuario@ejemplo.com"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cantidad de puntos
                        </label>
                        <input type="number" 
                               name="puntos" 
                               min="1"
                               max="10000"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="50"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo
                        </label>
                        <input type="text" 
                               name="motivo" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="Penalización por incumplimiento"
                               required>
                    </div>
                    <button type="submit" 
                            class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition">
                        ⚠️ Descontar Puntos
                    </button>
                </form>
            </div>
        </div>

        <!-- Acciones de Habilidades -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Aprobar Todas las Habilidades Pendientes -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-blue-600 mb-4 flex items-center">
                    🎯 Aprobar Todas las Pendientes
                </h3>
                <p class="text-gray-600 mb-4">
                    Aprobar todas las habilidades que están pendientes de revisión.
                    <br><span class="text-sm text-red-500">⚠️ Esta acción no se puede deshacer</span>
                </p>
                <form method="POST" action="{{ route('admin.acciones-rapidas.aprobar-todas-habilidades') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="confirmar" value="1" required class="mr-2">
                            <span class="text-sm text-gray-700">Confirmo que quiero aprobar todas las habilidades pendientes</span>
                        </label>
                    </div>
                    <button type="submit" 
                            class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition">
                        🎯 Aprobar Todas
                    </button>
                </form>
            </div>

            <!-- Limpiar Notificaciones -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-purple-600 mb-4 flex items-center">
                    🧹 Limpiar Sistema
                </h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.acciones-rapidas.limpiar-notificaciones') }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-600 transition">
                            🔔 Limpiar Notificaciones Antiguas
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.acciones-rapidas.limpiar-logs') }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600 transition">
                            📋 Limpiar Logs Antiguos
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.acciones-rapidas.optimizar-bd') }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-indigo-500 text-white py-2 rounded-lg hover:bg-indigo-600 transition">
                            ⚡ Optimizar Base de Datos
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                📈 Vista Rápida del Sistema
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $usuariosPendientes ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Usuarios Nuevos</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $habilidadesPendientes ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Habilidades Pendientes</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <div class="text-2xl font-bold text-red-600">{{ $denunciasPendientes ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Denuncias Nuevas</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600">{{ $truequesActivos ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Trueques Activos</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ $usuariosSuspendidos ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Usuarios Suspendidos</div>
                </div>
                <div class="text-center p-4 bg-indigo-50 rounded-lg">
                    <div class="text-2xl font-bold text-indigo-600">{{ $puntosCirculacion ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Puntos Circulando</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection