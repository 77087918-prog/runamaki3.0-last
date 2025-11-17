@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Editar Perfil</h1>
                <p class="text-sm text-gray-600 mt-1">Actualiza tu información personal y preferencias</p>
            </div>
            <a href="{{ route('perfil.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error mb-6">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Información Personal -->
    <div class="card fade-in mb-6">
        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
            <div class="w-12 h-12 bg-gradient-purple rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Información Personal</h2>
                <p class="text-sm text-gray-600">Actualiza tus datos y avatar</p>
            </div>
        </div>

        <form action="{{ route('perfil.actualizar') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Avatar -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-100">
                <label class="form-label text-indigo-900">Avatar</label>
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="relative">
                        <div class="w-24 h-24 rounded-full overflow-hidden ring-4 ring-white shadow-lg flex-shrink-0">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-purple flex items-center justify-center text-white text-3xl font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        @if(Auth::user()->avatar)
                            <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center ring-2 ring-white">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        @if(Auth::user()->avatar)
                            <p class="text-sm font-semibold text-indigo-900 mb-1">Avatar personalizado activo</p>
                            <p class="text-xs text-indigo-700 mb-3">Sube una nueva imagen para reemplazarlo</p>
                        @else
                            <p class="text-sm font-semibold text-indigo-900 mb-1">Sin avatar personalizado</p>
                            <p class="text-xs text-indigo-700 mb-3">Sube una imagen para personalizar tu perfil</p>
                        @endif
                        <label for="avatar" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-indigo-200 text-indigo-700 text-sm font-medium rounded-lg hover:bg-indigo-50 transition cursor-pointer shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Seleccionar imagen
                        </label>
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden">
                        <p class="text-xs text-indigo-600 mt-2">JPG, PNG • Máx. 2MB • Se redimensionará a 400x400px</p>
                    </div>
                </div>
            </div>

            <!-- Nombre -->
            <div>
                <label for="name" class="form-label">Nombre completo</label>
                <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" required class="form-input" placeholder="Ej: Juan Pérez">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" required class="form-input" placeholder="tu@email.com">
            </div>

            <!-- Biografía -->
            <div>
                <label for="bio" class="form-label">Biografía</label>
                <textarea name="bio" id="bio" rows="4" class="form-input" placeholder="Cuéntanos sobre ti, tus intereses y habilidades...">{{ old('bio', Auth::user()->bio) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar cambios
                </button>
                <a href="{{ route('perfil.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Cambiar Contraseña -->
    <div class="card fade-in">
        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Seguridad</h2>
                <p class="text-sm text-gray-600">Actualiza tu contraseña</p>
            </div>
        </div>
        
        <form action="{{ route('perfil.cambiar-password') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="form-label">Contraseña actual</label>
                <input type="password" name="current_password" id="current_password" required class="form-input" placeholder="••••••••">
            </div>

            <div>
                <label for="new_password" class="form-label">Nueva contraseña</label>
                <input type="password" name="new_password" id="new_password" required class="form-input" placeholder="••••••••">
                <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres. Incluye mayúsculas, minúsculas y números</p>
            </div>

            <div>
                <label for="new_password_confirmation" class="form-label">Confirmar nueva contraseña</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required class="form-input" placeholder="••••••••">
            </div>

            <div class="pt-4">
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Cambiar contraseña
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
