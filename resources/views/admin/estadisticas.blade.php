@extends('layouts.app')

@section('title', 'Estadísticas Avanzadas')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-red-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        📊 Estadísticas Avanzadas
                    </h1>
                    <p class="text-blue-100 mt-2">Análisis detallado de la plataforma Runa Maki</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                    ← Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Total Usuarios</p>
                        <p class="text-3xl font-bold">{{ $totalUsuarios }}</p>
                    </div>
                    <div class="text-4xl">👥</div>
                </div>
                <div class="mt-2 text-purple-100 text-sm">
                    +{{ $nuevosUsuarios }} este mes
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Habilidades</p>
                        <p class="text-3xl font-bold">{{ $totalHabilidades }}</p>
                    </div>
                    <div class="text-4xl">🎯</div>
                </div>
                <div class="mt-2 text-green-100 text-sm">
                    {{ $habilidadesPendientes }} pendientes
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Trueques</p>
                        <p class="text-3xl font-bold">{{ $totalTrueques }}</p>
                    </div>
                    <div class="text-4xl">🤝</div>
                </div>
                <div class="mt-2 text-blue-100 text-sm">
                    {{ $truequesCompletados }} completados
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm">Puntos Totales</p>
                        <p class="text-3xl font-bold">{{ $puntosCirculacion }}</p>
                    </div>
                    <div class="text-4xl">🪙</div>
                </div>
                <div class="mt-2 text-yellow-100 text-sm">
                    En circulación
                </div>
            </div>
        </div>

        <!-- Gráficos de Actividad -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Registros por Mes -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📈 Registros Mensuales</h3>
                <div class="space-y-3">
                    @foreach($registrosPorMes as $mes => $cantidad)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">{{ $mes }}</span>
                            <div class="flex items-center gap-2">
                                <div class="bg-blue-200 rounded-full h-2 flex-1 w-24">
                                    <div class="bg-blue-500 h-2 rounded-full" 
                                         style="width: {{ ($cantidad / max($registrosPorMes->values())) * 100 }}%"></div>
                                </div>
                                <span class="font-bold text-blue-600 w-8">{{ $cantidad }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Actividad por Categoría -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🎯 Habilidades por Categoría</h3>
                <div class="space-y-3">
                    @foreach($habilidadesPorCategoria as $categoria)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">{{ $categoria->categoria_nombre }}</span>
                            <div class="flex items-center gap-2">
                                <div class="bg-green-200 rounded-full h-2 flex-1 w-24">
                                    <div class="bg-green-500 h-2 rounded-full" 
                                         style="width: {{ ($categoria->total / $habilidadesPorCategoria->first()->total) * 100 }}%"></div>
                                </div>
                                <span class="font-bold text-green-600 w-8">{{ $categoria->total }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Estados de Trueques -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Estado de Trueques -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🤝 Estado de Trueques</h3>
                <div class="space-y-3">
                    @foreach($estadoTrueques as $estado)
                        <div class="flex items-center justify-between p-3 rounded-lg
                            @if($estado->estado === 'pendiente') bg-orange-50
                            @elseif($estado->estado === 'aceptado') bg-blue-50
                            @elseif($estado->estado === 'completado') bg-green-50
                            @elseif($estado->estado === 'rechazado') bg-red-50
                            @endif">
                            <span class="font-medium
                                @if($estado->estado === 'pendiente') text-orange-700
                                @elseif($estado->estado === 'aceptado') text-blue-700
                                @elseif($estado->estado === 'completado') text-green-700
                                @elseif($estado->estado === 'rechazado') text-red-700
                                @endif">
                                {{ ucfirst($estado->estado) }}
                            </span>
                            <span class="font-bold
                                @if($estado->estado === 'pendiente') text-orange-600
                                @elseif($estado->estado === 'aceptado') text-blue-600
                                @elseif($estado->estado === 'completado') text-green-600
                                @elseif($estado->estado === 'rechazado') text-red-600
                                @endif">
                                {{ $estado->total }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Usuarios Más Activos -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">⭐ Usuarios Más Activos</h3>
                <div class="space-y-3">
                    @foreach($usuariosMasActivos as $usuario)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <img src="{{ $usuario->avatar ?? '/images/avatar-default.png' }}" 
                                 alt="Avatar" 
                                 class="w-10 h-10 rounded-full">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $usuario->name }}</p>
                                <p class="text-sm text-gray-500">{{ $usuario->trueques_count }} trueques</p>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-indigo-600">{{ $usuario->puntos_runa }}</span>
                                <p class="text-xs text-gray-500">puntos</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tipos de Trueque -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🔄 Tipos de Trueque</h3>
                <div class="space-y-3">
                    @foreach($tiposTrueque as $tipo)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-purple-50">
                            <span class="font-medium text-purple-700">
                                @if($tipo->tipo_trueque === 'habilidad_por_habilidad')
                                    Habilidad ↔ Habilidad
                                @elseif($tipo->tipo_trueque === 'habilidad_por_puntos')
                                    Habilidad → Puntos
                                @elseif($tipo->tipo_trueque === 'puntos_por_habilidad')
                                    Puntos → Habilidad
                                @endif
                            </span>
                            <span class="font-bold text-purple-600">{{ $tipo->total }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Denuncias y Seguridad -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Estado de Denuncias -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🚨 Estado de Denuncias</h3>
                <div class="space-y-3">
                    @foreach($estadoDenuncias as $estado)
                        <div class="flex items-center justify-between p-3 rounded-lg
                            @if($estado->estado === 'pendiente') bg-red-50
                            @elseif($estado->estado === 'revisado') bg-yellow-50
                            @elseif($estado->estado === 'resuelto') bg-green-50
                            @endif">
                            <span class="font-medium
                                @if($estado->estado === 'pendiente') text-red-700
                                @elseif($estado->estado === 'revisado') text-yellow-700
                                @elseif($estado->estado === 'resuelto') text-green-700
                                @endif">
                                {{ ucfirst($estado->estado) }}
                            </span>
                            <span class="font-bold
                                @if($estado->estado === 'pendiente') text-red-600
                                @elseif($estado->estado === 'revisado') text-yellow-600
                                @elseif($estado->estado === 'resuelto') text-green-600
                                @endif">
                                {{ $estado->total }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Transacciones de Puntos -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">💰 Transacciones Recientes</h3>
                <div class="space-y-3">
                    @foreach($transaccionesRecientes as $transaccion)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ $transaccion->usuario->name }}</p>
                                <p class="text-sm text-gray-500">{{ $transaccion->descripcion }}</p>
                            </div>
                            <div class="text-right">
                                <span class="font-bold {{ $transaccion->tipo === 'suma' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaccion->tipo === 'suma' ? '+' : '-' }}{{ $transaccion->cantidad }}
                                </span>
                                <p class="text-xs text-gray-500">{{ $transaccion->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection