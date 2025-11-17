@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="card fade-in">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Editar habilidad</h1>
                <a href="{{ route('habilidades.show', $habilidad) }}" class="btn btn-secondary">← Cancelar</a>
            </div>

            @if(session('success'))
                <div class="alert-success mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-danger mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('habilidades.update', $habilidad) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="titulo" class="form-label">Título de la habilidad <span class="text-red-500">*</span></label>
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $habilidad->titulo) }}" required maxlength="100" class="form-input">
                </div>

                <div>
                    <label for="descripcion" class="form-label">Descripción <span class="text-red-500">*</span></label>
                    <textarea name="descripcion" id="descripcion" rows="5" required maxlength="500" class="form-input">{{ old('descripcion', $habilidad->descripcion) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="categoria_id" class="form-label">Categoría <span class="text-red-500">*</span></label>
                        <select name="categoria_id" id="categoria_id" required class="form-input">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $habilidad->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->icono }} {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="nivel" class="form-label">Nivel <span class="text-red-500">*</span></label>
                        <select name="nivel" id="nivel" required class="form-input">
                            <option value="">Selecciona un nivel</option>
                            <option value="principiante" {{ old('nivel', $habilidad->nivel) == 'principiante' ? 'selected' : '' }}>Principiante</option>
                            <option value="intermedio" {{ old('nivel', $habilidad->nivel) == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                            <option value="avanzado" {{ old('nivel', $habilidad->nivel) == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                            <option value="experto" {{ old('nivel', $habilidad->nivel) == 'experto' ? 'selected' : '' }}>Experto</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="horas_ofrecidas" class="form-label">Horas que ofreces <span class="text-red-500">*</span></label>
                        <input type="number" name="horas_ofrecidas" id="horas_ofrecidas" value="{{ old('horas_ofrecidas', $habilidad->horas_ofrecidas) }}" required min="1" max="100" class="form-input">
                    </div>

                    <div>
                        <label for="puntos_sugeridos" class="form-label">Puntos Runa sugeridos <span class="text-red-500">*</span></label>
                        <input type="number" name="puntos_sugeridos" id="puntos_sugeridos" value="{{ old('puntos_sugeridos', $habilidad->puntos_sugeridos) }}" required min="1" max="1000" class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Imagen actual</label>
                    @if($habilidad->imagen)
                        <div class="mb-3">
                            <img src="{{ Storage::url($habilidad->imagen) }}" alt="{{ $habilidad->titulo }}" class="w-48 h-48 object-cover rounded-lg">
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mb-3">No hay imagen cargada</p>
                    @endif
                    <label for="imagen" class="form-label">Cambiar imagen (opcional)</label>
                    <input type="file" name="imagen" id="imagen" accept="image/*" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG. Máximo 2MB</p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        <strong>Nota:</strong> Si modificas la habilidad, es posible que deba ser revisada nuevamente antes de publicarse.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="{{ route('habilidades.show', $habilidad) }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>

            <hr class="my-8">

            <div>
                <h2 class="text-xl font-bold text-red-600 mb-4">Zona de peligro</h2>
                <form action="{{ route('habilidades.destroy', $habilidad) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta habilidad? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Eliminar habilidad
                    </button>
                    <p class="text-sm text-gray-600 mt-2">Esta acción eliminará permanentemente esta habilidad.</p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
