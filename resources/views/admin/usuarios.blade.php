@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-red-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-6 mb-8 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        👥 Gestión de Usuarios
                    </h1>
                    <p class="text-blue-50 mt-2 font-medium">Administrar cuentas de usuario y permisos</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition font-medium">
                    ← Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Lista de Usuarios -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Lista de Usuarios</h2>
            </div>
            
            <!-- Vista Desktop - Tabla -->
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Habilidades</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Puntos</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Registro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($usuarios as $usuario)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img src="{{ $usuario->avatar_url }}" 
                                                 alt="{{ $usuario->name }}" 
                                                 class="w-8 h-8 rounded-full mr-3">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $usuario->name }}</div>
                                                @if($usuario->isAdmin())
                                                    <span class="text-xs text-red-600 dark:text-red-400 font-semibold">ADMIN</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $usuario->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs rounded-full font-semibold
                                            @if($usuario->estado === 'activo') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200
                                            @elseif($usuario->estado === 'suspendido') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst($usuario->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $usuario->habilidades_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $usuario->puntos_runa }} 🪙
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $usuario->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if(!$usuario->isAdmin())
                                            @if($usuario->estado === 'activo')
                                                <button onclick="suspenderUsuario({{ $usuario->id }}, '{{ $usuario->name }}')" 
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                                    Suspender
                                                </button>
                                            @else
                                                <form method="POST" action="{{ route('admin.usuarios.reactivar', $usuario) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 font-medium">
                                                        Reactivar
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">Admin protegido</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No hay usuarios registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Vista Mobile/Tablet - Cards -->
            <div class="block lg:hidden">
                <div class="space-y-4 p-4">
                    @forelse($usuarios as $usuario)
                        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 shadow-sm">
                            <!-- Header del Usuario -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <img src="{{ $usuario->avatar_url }}" 
                                         alt="{{ $usuario->name }}" 
                                         class="w-10 h-10 rounded-full mr-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $usuario->name }}</h3>
                                        @if($usuario->isAdmin())
                                            <span class="text-xs text-red-600 dark:text-red-400 font-semibold">ADMIN</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs rounded-full font-semibold
                                    @if($usuario->estado === 'activo') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200
                                    @elseif($usuario->estado === 'suspendido') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst($usuario->estado) }}
                                </span>
                            </div>

                            <!-- Información del Usuario -->
                            <div class="grid grid-cols-2 gap-3 mb-3 text-xs">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 uppercase font-medium">Email</p>
                                    <p class="text-gray-900 dark:text-gray-100 truncate">{{ $usuario->email }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 uppercase font-medium">Registro</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $usuario->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 uppercase font-medium">Habilidades</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $usuario->habilidades_count }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 uppercase font-medium">Puntos</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $usuario->puntos_runa }} 🪙</p>
                                </div>
                            </div>

                            <!-- Acciones -->
                            @if(!$usuario->isAdmin())
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                    @if($usuario->estado === 'activo')
                                        <button onclick="suspenderUsuario({{ $usuario->id }}, '{{ $usuario->name }}')" 
                                                class="w-full bg-red-600 hover:bg-red-700 text-white text-sm py-2 px-4 rounded-lg transition font-medium">
                                            🚫 Suspender Usuario
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('admin.usuarios.reactivar', $usuario) }}" class="w-full">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="w-full bg-green-600 hover:bg-green-700 text-white text-sm py-2 px-4 rounded-lg transition font-medium">
                                                ✅ Reactivar Usuario
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <p class="text-center text-gray-400 dark:text-gray-500 text-sm">Usuario Admin - Protegido</p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">👥</div>
                            <p class="text-gray-500 dark:text-gray-400">No hay usuarios registrados</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Paginación -->
            @if($usuarios->hasPages())
                <div class="mt-8">
                    {{ $usuarios->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para suspender usuario -->
<div id="modalSuspender" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4 border border-gray-200 dark:border-gray-700">
        <h3 class="text-xl font-bold text-red-600 dark:text-red-400 mb-4">⚠️ Suspender Usuario</h3>
        <form id="formSuspender" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <p class="text-gray-700 dark:text-gray-300 mb-2">¿Estás seguro de que quieres suspender a <strong id="nombreUsuario" class="text-gray-900 dark:text-gray-100"></strong>?</p>
                
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Motivo de suspensión
                </label>
                <textarea name="motivo" 
                          class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                          rows="3" 
                          required
                          placeholder="Explica por qué se suspende este usuario..."></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Días de suspensión
                </label>
                <select name="dias" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                    <option value="1">1 día</option>
                    <option value="3">3 días</option>
                    <option value="7">7 días</option>
                    <option value="15">15 días</option>
                    <option value="30">30 días</option>
                </select>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" 
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition">
                    Suspender
                </button>
                <button type="button" 
                        onclick="cerrarModal()" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg font-medium transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function suspenderUsuario(userId, userName) {
    document.getElementById('modalSuspender').classList.remove('hidden');
    document.getElementById('formSuspender').action = `/admin/usuarios/${userId}/suspender`;
    document.getElementById('nombreUsuario').textContent = userName;
}

function cerrarModal() {
    document.getElementById('modalSuspender').classList.add('hidden');
    document.getElementById('formSuspender').reset();
}
</script>
@endsection