@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex gap-3 md:gap-4 flex-wrap">
            <a href="{{ route('trueques.index') }}" 
               class="btn {{ !$filtro_estado ? 'btn-primary' : 'btn-secondary' }}">
                {{ __('app.view_all') }}
            </a>
            <a href="{{ route('trueques.index', ['estado' => 'pendiente']) }}" 
               class="btn {{ $filtro_estado === 'pendiente' ? 'btn-primary' : 'btn-secondary' }}">
                {{ __('app.pending_trades') }}
            </a>
            <a href="{{ route('trueques.index', ['estado' => 'aceptado']) }}" 
               class="btn {{ $filtro_estado === 'aceptado' ? 'btn-primary' : 'btn-secondary' }}">
                {{ __('app.accepted') }}
            </a>
            <a href="{{ route('trueques.index', ['estado' => 'completado']) }}" 
               class="btn {{ $filtro_estado === 'completado' ? 'btn-primary' : 'btn-secondary' }}">
                {{ __('app.completed_trades') }}
            </a>
        </div>
    </div>

    <!-- Lista de trueques -->
    @if($trueques->isEmpty())
        <div class="card text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('app.no_trades_found') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('app.search_skills') }}</p>
            <div class="mt-6">
                <a href="{{ route('habilidades.index') }}" class="btn btn-primary">
                    {{ __('app.available_skills') }}
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($trueques as $trueque)
                <div class="card hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Usuarios involucrados -->
                            <div class="flex items-center gap-3 md:gap-4 mb-3">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-900">{{ $trueque->usuarioOfrece->name }}</span>
                                    <span class="text-gray-500">{{ __('app.offer_skill') }}</span>
                                </div>
                                <svg class="h-3 w-3 md:h-4 md:w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                <div class="text-sm">
                                    <span class="text-gray-500">a</span>
                                    <span class="font-medium text-gray-900">{{ $trueque->usuarioRecibe->name }}</span>
                                </div>
                            </div>

                            <!-- Habilidades -->
                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div class="bg-indigo-50 rounded-lg p-3">
                                    <p class="text-xs text-indigo-600 font-medium mb-1">{{ __('app.offer_skill') }}</p>
                                    <p class="font-medium text-gray-900">{{ $trueque->habilidadOfrece->titulo }}</p>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-3">
                                    <p class="text-xs text-purple-600 font-medium mb-1">{{ __('app.request_skill') }}</p>
                                    <p class="font-medium text-gray-900">{{ $trueque->habilidadRecibe->titulo }}</p>
                                </div>
                            </div>

                            <!-- Metadata -->
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $trueque->puntos_intercambio }} Runas
                                </span>
                                <span>{{ $trueque->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Estado y acciones -->
                        <div class="ml-4 flex flex-col items-end gap-2">
                            @php
                                $estadoClasses = [
                                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'aceptado' => 'bg-blue-100 text-blue-800',
                                    'completado' => 'bg-green-100 text-green-800',
                                    'rechazado' => 'bg-red-100 text-red-800',
                                    'cancelado' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $estadoClasses[$trueque->estado] }}">
                                {{ ucfirst($trueque->estado) }}
                            </span>
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('trueques.chat', $trueque) }}" 
                                   class="btn bg-purple-600 hover:bg-purple-700 text-white btn-sm flex items-center space-x-1 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.965 8.965 0 01-4.57-1.22L3 21l2.22-5.43A8.965 8.965 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                                    </svg>
                                    <span>{{ __('app.chat') }}</span>
                                </a>
                                <a href="{{ route('trueques.show', $trueque) }}" class="btn btn-secondary btn-sm">
                                    {{ __('app.details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $trueques->links() }}
        </div>
    @endif
</div>
@endsection
