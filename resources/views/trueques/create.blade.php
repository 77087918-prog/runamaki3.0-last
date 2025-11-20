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

        <form action="{{ route('trueques.store') }}" method="POST" class="space-y-6" id="truequeForm">
            @csrf
            <input type="hidden" name="habilidad_recibe_id" value="{{ $habilidad_recibir->id }}">

            <!-- Tipo de Trueque -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    ¿Qué tipo de intercambio quieres proponer?
                </label>
                
                <div class="space-y-3">
                    <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition border-indigo-500 bg-indigo-50">
                        <input type="radio" 
                               name="tipo_trueque" 
                               value="habilidad_por_habilidad"
                               class="mt-1"
                               checked
                               onchange="toggleTipoTrueque()">
                        <div class="flex-1">
                            <h5 class="font-medium text-gray-900">🔄 Intercambio de Habilidades</h5>
                            <p class="text-sm text-gray-600 mt-1">Ofrezco una de mis habilidades a cambio de la tuya</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition border-gray-200">
                        <input type="radio" 
                               name="tipo_trueque" 
                               value="puntos_por_habilidad"
                               class="mt-1"
                               onchange="toggleTipoTrueque()">
                        <div class="flex-1">
                            <h5 class="font-medium text-gray-900">💰 Runas por Habilidad</h5>
                            <p class="text-sm text-gray-600 mt-1">Pago con puntos Runa para aprender tu habilidad</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Seleccionar habilidad a ofrecer (Solo para intercambio de habilidades) -->
            <div id="seccionHabilidad">
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

            <!-- Puntos a ofrecer (Solo para pago con puntos) -->
            <div id="seccionPuntos" style="display: none;">
                <label for="puntos_ofrecidos" class="block text-sm font-medium text-gray-700 mb-2">
                    ¿Cuántas Runas quieres ofrecer?
                </label>
                <div class="max-w-xs">
                    <input type="number" 
                           id="puntos_ofrecidos"
                           name="puntos_ofrecidos" 
                           min="1"
                           max="{{ auth()->user()->puntos_runa }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="{{ $habilidad_recibir->puntos_sugeridos }}">
                    <p class="text-xs text-gray-500 mt-1">
                        Tienes {{ auth()->user()->puntos_runa }} Runas disponibles
                    </p>
                </div>
                <div class="mt-3 text-sm text-gray-600">
                    <p>💡 <strong>Sugerencia:</strong> {{ $habilidad_recibir->puntos_sugeridos }} Runas (basado en la habilidad)</p>
                </div>
                @error('puntos_ofrecidos')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mensaje inicial opcional -->
            <div>
                <label for="mensaje_inicial" class="block text-sm font-medium text-gray-700 mb-2">
                    Mensaje inicial (opcional)
                </label>
                <textarea id="mensaje_inicial" 
                          name="mensaje_inicial" 
                          rows="4" 
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Ej: Hola, me interesa tu habilidad. ¿Cuándo podríamos coordinar?">{{ old('mensaje_inicial') }}</textarea>
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

<script>
function toggleTipoTrueque() {
    const tipoSeleccionado = document.querySelector('input[name="tipo_trueque"]:checked').value;
    const seccionHabilidad = document.getElementById('seccionHabilidad');
    const seccionPuntos = document.getElementById('seccionPuntos');
    
    if (tipoSeleccionado === 'habilidad_por_habilidad') {
        seccionHabilidad.style.display = 'block';
        seccionPuntos.style.display = 'none';
        // Hacer obligatorio el campo habilidad
        document.querySelectorAll('input[name="habilidad_ofrece_id"]').forEach(input => {
            input.setAttribute('required', 'required');
        });
        document.getElementById('puntos_ofrecidos').removeAttribute('required');
    } else if (tipoSeleccionado === 'puntos_por_habilidad') {
        seccionHabilidad.style.display = 'none';
        seccionPuntos.style.display = 'block';
        // Hacer obligatorio el campo puntos
        document.getElementById('puntos_ofrecidos').setAttribute('required', 'required');
        document.querySelectorAll('input[name="habilidad_ofrece_id"]').forEach(input => {
            input.removeAttribute('required');
        });
    }
}
</script>
@endsection
