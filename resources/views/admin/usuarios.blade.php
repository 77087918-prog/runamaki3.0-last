@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestión de Usuarios</h1>
                <p class="text-gray-600 mt-1">Administra los usuarios del sistema</p>
            </div>
            <div class="flex gap-2">
                <!-- Botones de exportación -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="btn btn-secondary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Exportar
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                        <a href="{{ route('admin.usuarios.exportar.csv', request()->query()) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            📊 Exportar a Excel
                        </a>
                        <a href="{{ route('admin.usuarios.exportar.pdf', request()->query()) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            📄 Exportar a PDF
                        </a>
                    </div>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    ← Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="card mb-6">
            <form method="GET" action="{{ route('admin.usuarios') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" 
                           placeholder="Nombre o email..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Todos</option>
                        <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="suspendido" {{ request('estado') === 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                        <option value="baneado" {{ request('estado') === 'baneado' ? 'selected' : '' }}>Baneado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select name="rol" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Todos</option>
                        <option value="usuario" {{ request('rol') === 'usuario' ? 'selected' : '' }}>Usuario</option>
                        <option value="admin" {{ request('rol') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                        Filtrar
                    </button>
                    <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="text-sm text-blue-600 font-medium">Total</div>
                <div class="text-2xl font-bold text-blue-700">{{ $usuarios->total() }}</div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="text-sm text-green-600 font-medium">Activos</div>
                <div class="text-2xl font-bold text-green-700">{{ $usuarios->where('estado', 'activo')->count() }}</div>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="text-sm text-yellow-600 font-medium">Suspendidos</div>
                <div class="text-2xl font-bold text-yellow-700">{{ $usuarios->where('estado', 'suspendido')->count() }}</div>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="text-sm text-purple-600 font-medium">Admins</div>
                <div class="text-2xl font-bold text-purple-700">{{ $usuarios->where('rol', 'admin')->count() }}</div>
            </div>
        </div>

        <!-- Lista de usuarios -->
        <div class="card">
            <!-- Tabla para desktop -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Stats</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Registro</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($usuarios as $usuario)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold">
                                            {{ substr($usuario->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $usuario->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $usuario->nivel_emoji }} {{ $usuario->nivel }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $usuario->email }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $usuario->puntos_runa }} Runas</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($usuario->estado === 'activo')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Activo</span>
                                    @elseif($usuario->estado === 'suspendido')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Suspendido</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Baneado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($usuario->rol === 'admin')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Admin</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">Usuario</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div>{{ $usuario->habilidades_count }} habilidades</div>
                                    <div>{{ $usuario->trueques_ofrecidos_count + $usuario->trueques_recibidos_count }} trueques</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $usuario->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('perfil.show', $usuario) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Ver perfil">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                        
                                        <!-- Cambiar Estado -->
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button @click="open = !open" class="text-gray-600 hover:text-gray-900" title="Cambiar estado">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                                <div class="py-1">
                                                    <form action="{{ route('admin.usuarios.cambiar-estado', $usuario->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="estado" value="activo">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-50">
                                                            ✓ Activar
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.usuarios.cambiar-estado', $usuario->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="estado" value="suspendido">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-yellow-700 hover:bg-yellow-50">
                                                            ⏸ Suspender
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.usuarios.cambiar-estado', $usuario->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="estado" value="baneado">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                            ✕ Banear
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Cambiar Rol -->
                                        @if($usuario->id !== auth()->id())
                                            <form action="{{ route('admin.usuarios.cambiar-rol', $usuario->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="rol" value="{{ $usuario->rol === 'admin' ? 'usuario' : 'admin' }}">
                                                <button type="submit" class="text-purple-600 hover:text-purple-900" title="{{ $usuario->rol === 'admin' ? 'Quitar admin' : 'Hacer admin' }}">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9.504 1.132a1 1 0 01.992 0l1.75 1a1 1 0 11-.992 1.736L10 3.152l-1.254.716a1 1 0 11-.992-1.736l1.75-1zM5.618 4.504a1 1 0 01-.372 1.364L5.016 6l.23.132a1 1 0 11-.992 1.736L4 7.723V8a1 1 0 01-2 0V6a.996.996 0 01.52-.878l1.734-.99a1 1 0 011.364.372zm8.764 0a1 1 0 011.364-.372l1.733.99A1.002 1.002 0 0118 6v2a1 1 0 11-2 0v-.277l-.254.145a1 1 0 11-.992-1.736l.23-.132-.23-.132a1 1 0 01-.372-1.364zm-7 4a1 1 0 011.364-.372L10 8.848l1.254-.716a1 1 0 11.992 1.736L11 10.58V12a1 1 0 11-2 0v-1.42l-1.246-.712a1 1 0 01-.372-1.364zM3 11a1 1 0 011 1v1.42l1.246.712a1 1 0 11-.992 1.736l-1.75-1A1 1 0 012 14v-2a1 1 0 011-1zm14 0a1 1 0 011 1v2a1 1 0 01-.504.868l-1.75 1a1 1 0 11-.992-1.736L16 13.42V12a1 1 0 011-1zm-9.618 5.504a1 1 0 011.364-.372l.254.145V16a1 1 0 112 0v.277l.254-.145a1 1 0 11.992 1.736l-1.735.992a.995.995 0 01-1.022 0l-1.735-.992a1 1 0 01-.372-1.364z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    No se encontraron usuarios
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Cards para móvil -->
            <div class="lg:hidden space-y-4 p-4">
                @forelse($usuarios as $usuario)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800">
                        <!-- Header con avatar y nombre -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($usuario->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $usuario->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->nivel_emoji }} {{ $usuario->nivel }}</div>
                                </div>
                            </div>
                            @if($usuario->rol === 'admin')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Admin</span>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="space-y-2 text-sm mb-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Email:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ Str::limit($usuario->email, 25) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Runas:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $usuario->puntos_runa }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Estadísticas:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $usuario->habilidades_count }} hab. / {{ $usuario->trueques_ofrecidos_count + $usuario->trueques_recibidos_count }} trueques</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Registro:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $usuario->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Estado:</span>
                                @if($usuario->estado === 'activo')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Activo</span>
                                @elseif($usuario->estado === 'suspendido')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Suspendido</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Baneado</span>
                                @endif
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('perfil.show', $usuario) }}" class="btn btn-secondary btn-sm flex-1 text-xs">
                                Ver Perfil
                            </a>
                            
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="btn btn-secondary btn-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" 
                                     class="absolute right-0 bottom-full mb-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 border border-gray-200 dark:border-gray-700">
                                    <!-- Estado -->
                                    <form action="{{ route('admin.usuarios.cambiar-estado', $usuario->id) }}" method="POST" class="block">
                                        @csrf
                                        <select name="estado" onchange="this.form.submit()" class="block w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                                            <option value="activo" {{ $usuario->estado === 'activo' ? 'selected' : '' }}>✓ Activo</option>
                                            <option value="suspendido" {{ $usuario->estado === 'suspendido' ? 'selected' : '' }}>⏸ Suspendido</option>
                                            <option value="baneado" {{ $usuario->estado === 'baneado' ? 'selected' : '' }}>🚫 Baneado</option>
                                        </select>
                                    </form>
                                    <!-- Rol -->
                                    @if($usuario->id !== auth()->id())
                                        <form action="{{ route('admin.usuarios.cambiar-rol', $usuario->id) }}" method="POST" class="block">
                                            @csrf
                                            <input type="hidden" name="rol" value="{{ $usuario->rol === 'admin' ? 'usuario' : 'admin' }}">
                                            <button type="submit" class="block w-full px-4 py-2 text-sm text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                {{ $usuario->rol === 'admin' ? '👤 Quitar Admin' : '⭐ Hacer Admin' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        No se encontraron usuarios
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            @if($usuarios->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $usuarios->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Alpine.js para dropdowns -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
