@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-red-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        👥 Gestión de Usuarios
                    </h1>
                    <p class="text-blue-100 mt-2">Administrar cuentas de usuario del sistema</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                    ← Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Usuarios -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Lista de Usuarios</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Habilidades</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Puntos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($usuarios as $usuario)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img src="{{ $usuario->avatar_url }}" 
                                             alt="{{ $usuario->name }}" 
                                             class="w-8 h-8 rounded-full mr-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $usuario->name }}</div>
                                            @if($usuario->isAdmin())
                                                <span class="text-xs text-red-600 font-semibold">ADMIN</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $usuario->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full font-semibold
                                        @if($usuario->estado === 'activo') bg-green-100 text-green-800
                                        @elseif($usuario->estado === 'suspendido') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $usuario->habilidades_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $usuario->puntos_runa }} 🪙
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $usuario->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if(!$usuario->isAdmin())
                                        @if($usuario->estado === 'activo')
                                            <button onclick="suspenderUsuario({{ $usuario->id }}, '{{ $usuario->name }}')" 
                                                    class="text-red-600 hover:text-red-900 mr-3">
                                                Suspender
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('admin.usuarios.reactivar', $usuario) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                                    Reactivar
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Admin protegido</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No hay usuarios registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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

<!-- Modal para suspender usuario -->
<div id="modalSuspender" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-red-600 mb-4">⚠️ Suspender Usuario</h3>
        <form id="formSuspender" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <p class="text-gray-700 mb-2">¿Estás seguro de que quieres suspender a <strong id="nombreUsuario"></strong>?</p>
                
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Motivo de suspensión
                </label>
                <textarea name="motivo" 
                          class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                          rows="3" 
                          required
                          placeholder="Explica por qué se suspende este usuario..."></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Días de suspensión
                </label>
                <select name="dias" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="1">1 día</option>
                    <option value="3">3 días</option>
                    <option value="7">7 días</option>
                    <option value="15">15 días</option>
                    <option value="30">30 días</option>
                </select>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" 
                        class="flex-1 bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                    Suspender
                </button>
                <button type="button" 
                        onclick="cerrarModalSuspender()" 
                        class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function suspenderUsuario(id, nombre) {
    document.getElementById('modalSuspender').classList.remove('hidden');
    document.getElementById('nombreUsuario').textContent = nombre;
    document.getElementById('formSuspender').action = `/admin/usuarios/${id}/suspender`;
}

function cerrarModalSuspender() {
    document.getElementById('modalSuspender').classList.add('hidden');
    document.getElementById('formSuspender').reset();
}
</script>
@endsection