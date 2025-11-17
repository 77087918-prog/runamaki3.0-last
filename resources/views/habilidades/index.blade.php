@extends('layouts.app')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <!-- Filtros de búsqueda -->
        <div class="p-6 border-b border-gray-200">
            <form action="{{ route('habilidades.buscar') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}"
                           placeholder="Buscar habilidades..." 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div class="w-64">
                    <select name="categoria" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias ?? [] as $cat)
                            <option value="{{ $cat->id }}" @selected(request('categoria') == $cat->id)>
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
            </form>
        </div>

        <!-- Lista de habilidades -->
        <div class="p-6">
            @if($habilidades->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-500">No se encontraron habilidades</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($habilidades as $habilidad)
                        <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                            <!-- Imagen de la habilidad -->
                            <div class="relative h-48 overflow-hidden bg-gradient-to-br from-purple-100 to-indigo-100">
                                <img src="{{ $habilidad->imagen_url }}" 
                                     alt="{{ $habilidad->titulo }}"
                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
                                     loading="lazy">
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full text-sm font-medium shadow-sm">
                                    {{ $habilidad->categoria->nombre }}
                                </div>
                            </div>

                            <!-- Contenido de la tarjeta -->
                            <div class="p-5">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 hover:text-indigo-600 transition mb-2">
                                            <a href="{{ route('habilidades.show', $habilidad) }}">
                                                {{ $habilidad->titulo }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            Por {{ $habilidad->usuario->name }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg text-sm font-bold whitespace-nowrap ml-2">
                                        {{ $habilidad->puntos_sugeridos }} PR
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                                    {{ $habilidad->descripcion }}
                                </p>

                                <div class="flex items-center justify-between text-sm text-gray-500 pt-3 border-t border-gray-100">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $habilidad->horas_ofrecidas }}h
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ $habilidad->visitas ?? 0 }}
                                    </span>
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