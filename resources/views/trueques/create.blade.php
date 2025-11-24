@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card">
        <h3 class="card-title mb-4">Nueva Propuesta de Intercambio</h3>

        <!-- Habilidad que quieres recibir -->
        <div class="bg-purple-50 rounded-lg p-4 mb-6">
            <p class="text-sm text-purple-700 font-medium mb-2">Quieres aprender:</p>
            <h4 class="text-lg font-bold text-gray-900">{{ $habilidad_recibir->titulo }}</h4>
            <p class="text-sm text-gray-600 mt-1">{{ $habilidad_recibir->descripcion }}</p>
            <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                <span>{{ $habilidad_recibir->usuario->name }}</span>
                <span>•</span>
                <span>{{ $habilidad_recibir->puntos_sugeridos }} Runas</span>
                <span>•</span>
                <span>{{ $habilidad_recibir->horas_ofrecidas }}h</span>
            </div>
        </div>

        <form action="{{ route('trueques.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="habilidad_recibe_id" value="{{ $habilidad_recibir->id }}">

            <!-- Seleccionar habilidad a ofrecer -->
            <div>
                <label for="habilidad_ofrece_id" class="block text-sm font-medium text-gray-700 mb-2">
                    ¿Qué habilidad quieres ofrecer a cambio?
                </label>
                
                @if($mis_habilidades->isEmpty())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">
                            No tienes habilidades aprobadas. 
                            <a href="{{ route('habilidades.create') }}" class="font-medium underline">Crea una habilidad</a>
                            para poder hacer trueques.
                        </p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($mis_habilidades as $habilidad)
                            <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition
                                {{ old('habilidad_ofrece_id') == $habilidad->id ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                <input type="radio" 
                                       name="habilidad_ofrece_id" 
                                       value="{{ $habilidad->id }}"
                                       class="mt-1"
                                       required
                                       {{ old('habilidad_ofrece_id') == $habilidad->id ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <h5 class="font-medium text-gray-900">{{ $habilidad->titulo }}</h5>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($habilidad->descripcion, 100) }}</p>
                                    <div class="flex items-center gap-3 mt-2 text-sm text-gray-500">
                                        <span class="text-indigo-600 font-medium">{{ $habilidad->puntos_sugeridos }} Runas</span>
                                        <span>•</span>
                                        <span>{{ $habilidad->horas_ofrecidas }}h</span>
                                        <span>•</span>
                                        <span>{{ $habilidad->categoria->nombre }}</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('habilidad_ofrece_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <!-- Mensaje inicial opcional -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-4">
                <label for="mensaje_inicial" class="block text-sm font-medium text-gray-700 mb-2 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.965 8.965 0 01-4.57-1.22L3 21l2.22-5.43A8.965 8.965 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                    </svg>
                    <span>💬 Mensaje inicial (opcional)</span>
                </label>
                <p class="text-sm text-gray-600 mb-3">
                    Se creará automáticamente un <strong>chat en tiempo real</strong> con el usuario. Puedes agregar un mensaje personalizado.
                </p>
                <textarea id="mensaje_inicial" 
                          name="mensaje_inicial" 
                          rows="4" 
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Ej: ¡Hola! Me interesa mucho tu habilidad. ¿Podríamos coordinar horarios? ✨">{{ old('mensaje_inicial') }}</textarea>
                @error('mensaje_inicial')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary flex-1">
                    Enviar Propuesta
                </button>
                <a href="{{ route('habilidades.show', $habilidad_recibir) }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
