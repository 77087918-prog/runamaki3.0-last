@extends('layouts.app')

@section('content')
<div class="py-4 sm:py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <h1 class="text-xl sm:text-2xl font-bold">{{ __('app.create_skill') }}</h1>
                <a href="{{ route('habilidades.index') }}" class="btn btn-secondary w-full sm:w-auto justify-center">← {{ __('app.cancel') }}</a>
            </div>

            @if($errors->any())
                <div class="alert-danger mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('habilidades.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="titulo" class="form-label">{{ __('app.skill_title') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required maxlength="100" class="form-input" placeholder="{{ __('app.search_example') }}">
                </div>

                <div>
                    <label for="descripcion" class="form-label">{{ __('app.skill_description') }} <span class="text-red-500">*</span></label>
                    <textarea name="descripcion" id="descripcion" rows="5" required maxlength="500" class="form-input" placeholder="{{ __('app.description') }}">{{ old('descripcion') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="categoria_id" class="form-label">{{ __('app.skill_category') }} <span class="text-red-500">*</span></label>
                        <select name="categoria_id" id="categoria_id" required class="form-input">
                            <option value="">{{ __('app.select') }}</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->icono }} {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="nivel" class="form-label">Nivel <span class="text-red-500">*</span></label>
                        <select name="nivel" id="nivel" required class="form-input">
                            <option value="">Selecciona un nivel</option>
                            <option value="principiante" {{ old('nivel') == 'principiante' ? 'selected' : '' }}>Principiante</option>
                            <option value="intermedio" {{ old('nivel') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                            <option value="avanzado" {{ old('nivel') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                            <option value="experto" {{ old('nivel') == 'experto' ? 'selected' : '' }}>Experto</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="horas_ofrecidas" class="form-label">Horas que ofreces <span class="text-red-500">*</span></label>
                        <input type="number" name="horas_ofrecidas" id="horas_ofrecidas" value="{{ old('horas_ofrecidas', 1) }}" required min="1" max="100" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Tiempo que puedes dedicar</p>
                    </div>

                    <div>
                        <label for="puntos_sugeridos" class="form-label">Puntos Runa sugeridos <span class="text-red-500">*</span></label>
                        <input type="number" name="puntos_sugeridos" id="puntos_sugeridos" value="{{ old('puntos_sugeridos', 10) }}" required min="1" max="1000" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Puntos que sugieres por esta habilidad</p>
                    </div>
                </div>

                <div>
                    <label for="imagen" class="form-label">Imagen (opcional)</label>
                    <input type="file" name="imagen" id="imagen" accept="image/*" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG. Máximo 2MB</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-2">📌 Información importante</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Tu habilidad será revisada antes de publicarse</li>
                        <li>•Recibirás una notificación cuando sea aprobada</li>
                        <li>• Puedes editar o eliminar tu habilidad en cualquier momento</li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="btn btn-primary w-full sm:w-auto justify-center">{{ __('app.create') }}</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary w-full sm:w-auto justify-center">{{ __('app.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
