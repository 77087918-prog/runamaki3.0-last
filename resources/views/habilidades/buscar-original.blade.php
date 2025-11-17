@extends('layouts.app')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <!-- Filtros de búsqueda -->
        <div class="p-6 border-b border-gray-200">
            <form action="{{ route('habilidades.buscar') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[240px]">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Texto</label>
                    <input type="text" 
                           name="q" 
                           value="{{ $query }}"
                           placeholder="Buscar habilidades..." 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div class="w-64 min-w-[180px]">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Categoría</label>
                    <select name="categoria" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Todas</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" @selected($categoria == $cat->id)>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                        Buscar
                    </button>
                </div>
                @if($query || $categoria)
                    <div>
                        <a href="{{ route('habilidades.index') }}" class="text-xs text-gray-500 hover:text-gray-700">Limpiar filtros</a>
                    </div>
                @endif
            </form>
            @if($query)
                <p class="text-xs text-gray-500 mt-3">Buscando: <span class="font-medium text-gray-700">“{{ $query }}”</span></p>
            @endif
        </div>

        <!-- Lista de resultados -->
        <div class="p-6">
            @if($habilidades->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-500">No se encontraron habilidades con los filtros actuales.</p>
                    <a href="{{ route('habilidades.index') }}" class="inline-block mt-4 text-sm text-indigo-600 hover:text-indigo-800">Ver todas las habilidades</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($habilidades as $habilidad)
                        <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                            <div class="relative h-44 overflow-hidden bg-gradient-to-br from-purple-100 to-indigo-100">
                                <img src="{{ $habilidad->imagen_url }}" 
                                     alt="{{ $habilidad->titulo }}"
                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
                                     loading="lazy">
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-medium shadow-sm">
                                    {{ $habilidad->categoria->nombre }}
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="text-base font-bold text-gray-900 mb-1">
                                    <a href="{{ route('habilidades.show', $habilidad) }}" class="hover:text-indigo-600 transition">
                                        {{ $habilidad->titulo }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-600 mb-2">Por {{ $habilidad->usuario->name }}</p>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $habilidad->descripcion }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                                    <span>{{ $habilidad->horas_ofrecidas }}h</span>
                                    <span>{{ $habilidad->puntos_sugeridos }} PR</span>
                                    <span>{{ $habilidad->visitas ?? 0 }} vistas</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $habilidades->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection