@extends('layouts.app')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">⚙️ Búsqueda Avanzada</h2>
                    <p class="text-sm text-gray-600">Filtra habilidades con múltiples criterios</p>
                </div>
                <a href="{{ route('habilidades.buscar') }}" 
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition text-sm">
                    🔍 Búsqueda simple
                </a>
            </div>
            
            <form action="{{ route('habilidades.busqueda-avanzada') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Título -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            📝 Título de la habilidad
                        </label>
                        <input type="text" 
                               name="titulo" 
                               value="{{ $filtros['titulo'] ?? '' }}"
                               placeholder="Ej: programación, cocina..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <p class="text-xs text-gray-500 mt-1">Busca en el título específicamente</p>
                    </div>
                    
                    <!-- Descripción -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            📄 Descripción contiene
                        </label>
                        <input type="text" 
                               name="descripcion" 
                               value="{{ $filtros['descripcion'] ?? '' }}"
                               placeholder="Palabras en la descripción..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <p class="text-xs text-gray-500 mt-1">Busca en la descripción detallada</p>
                    </div>
                    
                    <!-- Usuario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            👤 Nombre del usuario
                        </label>
                        <input type="text" 
                               name="usuario" 
                               value="{{ $filtros['usuario'] ?? '' }}"
                               placeholder="Nombre del instructor..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <p class="text-xs text-gray-500 mt-1">Busca por quien ofrece la habilidad</p>
                    </div>
                    
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Categoría -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            📂 Categoría
                        </label>
                        <select name="categoria_id" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" @selected(($filtros['categoria_id'] ?? '') == $cat->id)>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Puntos Runa (rango) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            💎 Puntos Runa
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" 
                                   name="puntos_min" 
                                   value="{{ $filtros['puntos_min'] ?? '' }}"
                                   placeholder="Min"
                                   min="1" max="1000"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <input type="number" 
                                   name="puntos_max" 
                                   value="{{ $filtros['puntos_max'] ?? '' }}"
                                   placeholder="Max"
                                   min="1" max="1000"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Rango de puntos sugeridos</p>
                    </div>
                    
                    <!-- Horas (rango) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ⏰ Horas ofrecidas
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" 
                                   name="horas_min" 
                                   value="{{ $filtros['horas_min'] ?? '' }}"
                                   placeholder="Min"
                                   min="1" max="100"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <input type="number" 
                                   name="horas_max" 
                                   value="{{ $filtros['horas_max'] ?? '' }}"
                                   placeholder="Max"
                                   min="1" max="100"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Duración de la enseñanza</p>
                    </div>
                    
                    <!-- Ordenamiento -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            📊 Ordenar por
                        </label>
                        <select name="orden" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="fecha" @selected(($filtros['orden'] ?? 'fecha') === 'fecha')>Más recientes</option>
                            <option value="puntos" @selected(($filtros['orden'] ?? '') === 'puntos')>Mayor puntuación</option>
                            <option value="visitas" @selected(($filtros['orden'] ?? '') === 'visitas')>Más populares</option>
                            <option value="alfabetico" @selected(($filtros['orden'] ?? '') === 'alfabetico')>Alfabético</option>
                        </select>
                    </div>
                    
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex gap-3">
                        <button type="submit" 
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">
                            🔍 Buscar con filtros
                        </button>
                        <a href="{{ route('habilidades.busqueda-avanzada') }}" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">
                            🗑️ Limpiar filtros
                        </a>
                    </div>
                    
                    <div class="text-sm text-gray-500">
                        Total: <strong>{{ $habilidades->total() }}</strong> resultados
                    </div>
                </div>
            </form>
        </div>

        <!-- Resultados -->
        <div class="p-6">
            @if($habilidades->isEmpty())
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🎯</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron habilidades</h3>
                    <p class="text-gray-500 mb-4">
                        Los filtros aplicados son muy específicos.
                    </p>
                    
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>💡 Intenta:</strong></p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Ampliar los rangos de puntos y horas</li>
                            <li>Usar términos más generales</li>
                            <li>Seleccionar "Todas las categorías"</li>
                            <li>Reducir la cantidad de filtros</li>
                        </ul>
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('habilidades.busqueda-avanzada') }}" 
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            🗑️ Limpiar todos los filtros
                        </a>
                    </div>
                </div>
            @else
                <!-- Filtros activos -->
                @if(array_filter($filtros))
                    <div class="mb-6 p-4 bg-blue-50 rounded-md">
                        <p class="text-sm font-medium text-blue-800 mb-2">🔧 Filtros activos:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($filtros as $key => $value)
                                @if($value)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        @switch($key)
                                            @case('titulo')
                                                📝 Título: {{ $value }}
                                                @break
                                            @case('descripcion')
                                                📄 Descripción: {{ $value }}
                                                @break
                                            @case('usuario')
                                                👤 Usuario: {{ $value }}
                                                @break
                                            @case('categoria_id')
                                                📂 {{ $categorias->find($value)->nombre ?? 'Categoría' }}
                                                @break
                                            @case('puntos_min')
                                                💎 Min: {{ $value }} PR
                                                @break
                                            @case('puntos_max')
                                                💎 Max: {{ $value }} PR
                                                @break
                                            @case('horas_min')
                                                ⏰ Min: {{ $value }}h
                                                @break
                                            @case('horas_max')
                                                ⏰ Max: {{ $value }}h
                                                @break
                                            @case('orden')
                                                📊 {{ ucfirst($value) }}
                                                @break
                                        @endswitch
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($habilidades as $habilidad)
                        <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 group">
                            <div class="relative h-44 overflow-hidden bg-gradient-to-br from-purple-100 to-indigo-100">
                                <img src="{{ $habilidad->imagen_url }}" 
                                     alt="{{ $habilidad->titulo }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                     loading="lazy">
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-medium shadow-sm">
                                    {{ $habilidad->categoria_icono }} {{ $habilidad->categoria->nombre }}
                                </div>
                                @if($habilidad->visitas > 50)
                                    <div class="absolute top-3 left-3 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                        🔥 Popular
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="text-base font-bold text-gray-900 mb-1 line-clamp-2">
                                    <a href="{{ route('habilidades.show', $habilidad) }}" class="hover:text-indigo-600 transition">
                                        {{ $habilidad->titulo }}
                                    </a>
                                </h3>
                                <div class="flex items-center gap-2 mb-2">
                                    <img src="{{ $habilidad->usuario->avatar_url }}" 
                                         alt="{{ $habilidad->usuario->name }}"
                                         class="w-5 h-5 rounded-full">
                                    <p class="text-xs text-gray-600">{{ $habilidad->usuario->name }}</p>
                                    <span class="text-xs text-yellow-500">
                                        ⭐ {{ number_format($habilidad->usuario->reputacion, 1) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $habilidad->descripcion }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                                    <span class="flex items-center gap-1">
                                        ⏰ {{ $habilidad->horas_ofrecidas }}h
                                    </span>
                                    <span class="flex items-center gap-1 font-medium text-indigo-600">
                                        💎 {{ $habilidad->puntos_sugeridos }} PR
                                    </span>
                                    <span class="flex items-center gap-1">
                                        👁️ {{ $habilidad->visitas ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Paginación -->
                <div class="mt-8">
                    {{ $habilidades->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection