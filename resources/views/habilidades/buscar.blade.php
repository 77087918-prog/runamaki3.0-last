@extends('layouts.app')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <!-- Búsqueda mejorada con autocompletado -->
        <div class="p-6 border-b border-gray-200">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">🔍 {{ __('app.smart_search') }}</h2>
                <p class="text-sm text-gray-600">{{ __('app.search_by_keywords') }}</p>
            </div>
            
            <form action="{{ route('habilidades.buscar') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Búsqueda principal con autocompletado -->
                    <div class="md:col-span-2 relative">
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            🔍 {{ __('app.search_skills') }}
                        </label>
                        <input type="text" 
                               id="search-input"
                               name="q" 
                               value="{{ $query }}"
                               placeholder="{{ __('app.search_example') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               autocomplete="off">
                        
                        <!-- Lista de sugerencias de autocompletado -->
                        <div id="autocomplete-results" class="hidden absolute z-10 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto">
                        </div>
                    </div>
                    
                    <!-- Filtro por categoría -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">📂 {{ __('app.category') }}</label>
                        <select name="categoria" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">{{ __('app.all_categories') }}</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" @selected($categoria == $cat->id)>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Ordenamiento -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">📊 {{ __('app.sort_by') }}</label>
                        <select name="orden" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="relevancia" @selected($ordenPor === 'relevancia')>Relevancia</option>
                            <option value="fecha" @selected($ordenPor === 'fecha')>Más recientes</option>
                            <option value="puntos" @selected($ordenPor === 'puntos')>Mayor puntuación</option>
                            <option value="visitas" @selected($ordenPor === 'visitas')>Más populares</option>
                            <option value="alfabetico" @selected($ordenPor === 'alfabetico')>Alfabético</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">
                            🔍 Buscar
                        </button>
                        <a href="{{ route('habilidades.busqueda-avanzada') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition text-sm">
                            ⚙️ Búsqueda avanzada
                        </a>
                    </div>
                    
                    @if($query || $categoria)
                        <a href="{{ route('habilidades.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
                            ✕ Limpiar filtros
                        </a>
                    @endif
                </div>
            </form>
            
            <!-- Sugerencias de búsqueda -->
            @if(!empty($sugerencias) && count($sugerencias) > 0)
                <div class="mt-4 p-3 bg-blue-50 rounded-md">
                    <p class="text-xs font-medium text-blue-800 mb-2">💡 Búsquedas relacionadas:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sugerencias as $sugerencia)
                            <a href="{{ route('habilidades.buscar', ['q' => $sugerencia]) }}" 
                               class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full hover:bg-blue-200 transition">
                                {{ $sugerencia }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Información de resultados -->
            @if($query)
                <div class="mt-4 p-3 bg-gray-50 rounded-md">
                    <p class="text-sm text-gray-600">
                        📊 <strong>{{ number_format($totalResultados) }}</strong> resultados para 
                        <span class="font-medium text-gray-800">"{{ $query }}"</span>
                        @if($categoria)
                            en la categoría <span class="font-medium">{{ $categorias->find($categoria)->nombre }}</span>
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Lista de resultados -->
        <div class="p-6">
            @if($habilidades->isEmpty())
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron resultados</h3>
                    <p class="text-gray-500 mb-4">
                        @if($query)
                            No encontramos habilidades que coincidan con "<strong>{{ $query }}</strong>"
                        @else
                            No hay habilidades que coincidan con los filtros seleccionados.
                        @endif
                    </p>
                    
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>💡 Sugerencias:</strong></p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Verifica la ortografía de las palabras</li>
                            <li>Intenta con términos más generales</li>
                            <li>Usa sinónimos o palabras relacionadas</li>
                            <li>Revisa los filtros aplicados</li>
                        </ul>
                    </div>
                    
                    <div class="mt-6 flex justify-center gap-4">
                        <a href="{{ route('habilidades.index') }}" 
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            Ver todas las habilidades
                        </a>
                        <a href="{{ route('habilidades.busqueda-avanzada') }}" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">
                            Búsqueda avanzada
                        </a>
                    </div>
                </div>
            @else
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

    <!-- JavaScript para autocompletado -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const autocompleteResults = document.getElementById('autocomplete-results');
            let timeoutId = null;

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                // Limpiar timeout anterior
                if (timeoutId) {
                    clearTimeout(timeoutId);
                }
                
                if (query.length < 2) {
                    autocompleteResults.classList.add('hidden');
                    return;
                }
                
                // Esperar 300ms antes de hacer la búsqueda
                timeoutId = setTimeout(() => {
                    fetch(`{{ route('habilidades.autocompletar') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(suggestions => {
                            if (suggestions.length > 0) {
                                autocompleteResults.innerHTML = suggestions.map(suggestion => 
                                    `<div class="px-4 py-2 hover:bg-gray-100 cursor-pointer autocomplete-item" data-value="${suggestion}">
                                        <span class="text-sm">${suggestion}</span>
                                    </div>`
                                ).join('');
                                autocompleteResults.classList.remove('hidden');
                                
                                // Agregar evento click a cada sugerencia
                                document.querySelectorAll('.autocomplete-item').forEach(item => {
                                    item.addEventListener('click', function() {
                                        searchInput.value = this.dataset.value;
                                        autocompleteResults.classList.add('hidden');
                                        // Enviar formulario automáticamente
                                        searchInput.closest('form').submit();
                                    });
                                });
                            } else {
                                autocompleteResults.classList.add('hidden');
                            }
                        })
                        .catch(() => {
                            autocompleteResults.classList.add('hidden');
                        });
                }, 300);
            });
            
            // Ocultar sugerencias al hacer click fuera
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !autocompleteResults.contains(e.target)) {
                    autocompleteResults.classList.add('hidden');
                }
            });
            
            // Manejar teclas de navegación
            searchInput.addEventListener('keydown', function(e) {
                const items = autocompleteResults.querySelectorAll('.autocomplete-item');
                const activeItem = autocompleteResults.querySelector('.autocomplete-item.bg-gray-100');
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!activeItem) {
                        items[0]?.classList.add('bg-gray-100');
                    } else {
                        activeItem.classList.remove('bg-gray-100');
                        const next = activeItem.nextElementSibling;
                        if (next) {
                            next.classList.add('bg-gray-100');
                        } else {
                            items[0]?.classList.add('bg-gray-100');
                        }
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (activeItem) {
                        activeItem.classList.remove('bg-gray-100');
                        const prev = activeItem.previousElementSibling;
                        if (prev) {
                            prev.classList.add('bg-gray-100');
                        } else {
                            items[items.length - 1]?.classList.add('bg-gray-100');
                        }
                    }
                } else if (e.key === 'Enter') {
                    if (activeItem) {
                        e.preventDefault();
                        activeItem.click();
                    }
                } else if (e.key === 'Escape') {
                    autocompleteResults.classList.add('hidden');
                }
            });
        });
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .autocomplete-item:hover {
            background-color: #f3f4f6;
        }
    </style>
@endsection