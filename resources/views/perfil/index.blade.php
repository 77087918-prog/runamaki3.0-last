@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header con Avatar y Stats Principales -->
    <div class="card fade-in">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <!-- Avatar y Info Básica -->
            <div class="flex items-center gap-4 flex-1">
                <div class="relative">
                    <div class="w-24 h-24 bg-gradient-purple rounded-full flex items-center justify-center text-white text-4xl font-bold flex-shrink-0 overflow-hidden ring-4 ring-indigo-100 shadow-lg">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <!-- Badge de nivel -->
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-3 py-1 bg-white rounded-full shadow-md border-2 border-indigo-100">
                        <span class="text-xs font-bold text-indigo-600">{{ $user->nivel }}</span>
                    </div>
                </div>
                <div class="flex-1">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-600 mb-3">{{ $user->email }}</p>
                    
                    <!-- Reputación y Runas -->
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5 bg-gradient-to-r from-yellow-50 to-orange-50 px-3 py-1.5 rounded-lg border border-yellow-100">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= floor($user->reputacion) ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                            <span class="text-sm font-bold text-yellow-700 ml-1">{{ number_format($user->reputacion, 1) }}</span>
                        </div>
                        <div class="flex items-center gap-2 bg-gradient-to-r from-purple-50 to-indigo-50 px-3 py-1.5 rounded-lg border border-purple-100">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-bold text-purple-700">{{ $user->puntos_runa }} <span class="font-normal">Runas</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón Editar -->
            <a href="{{ route('perfil.editar') }}" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar perfil
            </a>
        </div>

        <!-- Biografía -->
        @if($user->bio)
            <div class="mt-6 pt-6 border-t border-gray-100">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h3 class="font-semibold text-gray-900">Sobre mí</h3>
                </div>
                <p class="text-gray-700 leading-relaxed">{{ $user->bio }}</p>
            </div>
        @endif
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card fade-in bg-gradient-to-br from-blue-50 to-indigo-50 border-indigo-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide font-semibold text-indigo-600 mb-1">Miembro desde</p>
                    <p class="text-2xl font-bold text-indigo-900">{{ $user->created_at->format('M Y') }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="card fade-in bg-gradient-to-br from-green-50 to-emerald-50 border-green-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide font-semibold text-green-600 mb-1">Habilidades</p>
                    <p class="text-2xl font-bold text-green-900">{{ $user->habilidades()->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="card fade-in bg-gradient-to-br from-purple-50 to-pink-50 border-purple-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide font-semibold text-purple-600 mb-1">Trueques</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $truequesCompletados }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Mis Habilidades -->
    <div class="card fade-in">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Mis Habilidades</h2>
                    <p class="text-sm text-gray-600">Gestiona tus conocimientos compartidos</p>
                </div>
            </div>
            <a href="{{ route('habilidades.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva
            </a>
        </div>

        @if($habilidades->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-600 mb-4">No tienes habilidades publicadas aún</p>
                <a href="{{ route('habilidades.create') }}" class="btn btn-primary">Publicar mi primera habilidad</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($habilidades as $habilidad)
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-lg hover:border-indigo-200 transition group">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $habilidad->titulo }}</h3>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full flex-shrink-0
                                @if($habilidad->estado === 'aprobada') bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200
                                @elseif($habilidad->estado === 'pendiente') bg-yellow-100 text-yellow-700 ring-1 ring-yellow-200
                                @else bg-red-100 text-red-700 ring-1 ring-red-200
                                @endif">
                                {{ ucfirst($habilidad->estado) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $habilidad->descripcion }}</p>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-medium">{{ $habilidad->horas_necesarias }}h</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium">{{ $habilidad->puntos_runa }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>{{ $habilidad->visitas }}</span>
                                </div>
                            </div>
                            <a href="{{ route('habilidades.show', $habilidad) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                                Ver
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Logros -->
    <div class="card fade-in">
        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
            <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Logros</h2>
                <p class="text-sm text-gray-600">Tus insignias de éxito</p>
            </div>
        </div>

        @if($user->logros->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <p class="text-gray-600 mb-2">¡No has desbloqueado logros aún!</p>
                <p class="text-sm text-gray-500">Completa trueques y participa en la comunidad para conseguirlos</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($user->logros as $logro)
                    <div class="text-center p-6 border-2 border-gray-200 rounded-xl hover:border-yellow-300 hover:bg-yellow-50 transition group">
                        <div class="text-5xl mb-3 group-hover:scale-110 transition">{{ $logro->icono }}</div>
                        <div class="font-bold text-sm text-gray-900 mb-1">{{ $logro->nombre }}</div>
                        <div class="text-xs text-gray-600 leading-relaxed">{{ $logro->descripcion }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Transacciones Recientes -->
    <div class="card fade-in">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Transacciones Recientes</h2>
                    <p class="text-sm text-gray-600">Historial de Runas</p>
                </div>
            </div>
            <a href="{{ route('perfil.transacciones') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                Ver todas
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        @if($transacciones->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-gray-600">No tienes transacciones aún</p>
            </div>
        @else
            <div class="space-y-1">
                @foreach($transacciones as $transaccion)
                    <div class="flex items-center justify-between py-4 px-4 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $transaccion->cantidad > 0 ? 'bg-emerald-100' : 'bg-red-100' }}">
                                @if($transaccion->cantidad > 0)
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $transaccion->concepto }}</div>
                                <div class="text-xs text-gray-500">{{ $transaccion->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-lg {{ $transaccion->cantidad > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $transaccion->cantidad > 0 ? '+' : '' }}{{ $transaccion->cantidad }}
                            </div>
                            <div class="text-xs text-gray-500">Runas</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
