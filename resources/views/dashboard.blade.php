@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header con saludo -->
        <div class="card fade-in mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">¡Hola, {{ $user->name }}!</h1>
                    <p class="text-gray-600 mt-1">{{ __('Trueque digital, comunidad real.') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600">Tu nivel actual</div>
                    <div class="text-2xl font-bold bg-gradient-to-r from-yellow-600 to-yellow-800 bg-clip-text text-transparent">
                        {{ $user->nivel_emoji }} {{ $user->nivel }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Puntos Runa -->
        <div class="card fade-in mb-6 bg-gradient-purple text-gray-900">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <div class="text-sm opacity-90">Puntos Runa</div>
                    <div class="text-4xl font-bold mt-1">{{ $user->puntos_runa }}</div>
                    <div class="text-sm opacity-75 mt-2">
                        @if($user->puntos_para_siguiente_nivel > 0)
                            {{ $user->puntos_para_siguiente_nivel }} puntos para {{ $user->puntos_runa >= 200 ? 'Platino' : ($user->puntos_runa >= 100 ? 'Oro' : 'Plata') }}
                        @else
                            ¡Nivel máximo alcanzado!
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-sm opacity-90">Reputación</div>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="text-3xl font-bold">{{ number_format($user->reputacion, 1) }}</div>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= floor($user->reputacion) ? 'text-yellow-300' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                    </div>
                </div>
                <div>
                    <div class="text-sm opacity-90">Progreso al siguiente nivel</div>
                    <div class="mt-2">
                        <div class="w-full bg-white/20 rounded-full h-3">
                            <div class="bg-yellow-300 h-3 rounded-full transition-all" style="width: {{ $user->progreso_nivel }}%"></div>
                        </div>
                        <div class="text-xs opacity-75 mt-1">{{ number_format($user->progreso_nivel, 0) }}% completado</div>
                    </div>
                </div>
            </div>
        </div>

            <div class="mt-8">
                <h2 class="card-title mb-4">Mis Trueques</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 rounded">
                        <div class="text-sm text-yellow-800 font-medium">Pendientes</div>
                        <div class="text-2xl font-bold text-yellow-900 mt-1">{{ $truequesRecibidos }}</div>
                        <a href="{{ route('trueques.index', ['estado' => 'pendiente']) }}" class="text-sm text-yellow-700 hover:underline mt-1 inline-block">Ver todas →</a>
                    </div>
                    <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded">
                        <div class="text-sm text-blue-800 font-medium">Activos</div>
                        <div class="text-2xl font-bold text-blue-900 mt-1">{{ $truequesActivos }}</div>
                        <a href="{{ route('trueques.index', ['estado' => 'aceptado']) }}" class="text-sm text-blue-700 hover:underline mt-1 inline-block">Ver todas →</a>
                    </div>
                    <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded">
                        <div class="text-sm text-green-800 font-medium">Completados</div>
                        <div class="text-2xl font-bold text-green-900 mt-1">{{ $truequesCompletados }}</div>
                        <a href="{{ route('trueques.index', ['estado' => 'completado']) }}" class="text-sm text-green-700 hover:underline mt-1 inline-block">Ver todas →</a>
                    </div>
                </div>
            </div>

            @if($ultimosTrueques->isNotEmpty())
            <div class="mt-8">
                <h2 class="card-title mb-4">Últimos Trueques</h2>
                <div class="space-y-3">
                    @foreach($ultimosTrueques as $trueque)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                            @if($trueque->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                            @elseif($trueque->estado === 'aceptado') bg-blue-100 text-blue-800
                                            @elseif($trueque->estado === 'completado') bg-green-100 text-green-800
                                            @elseif($trueque->estado === 'rechazado') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($trueque->estado) }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{ $trueque->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="mt-2 flex items-center gap-2 text-sm">
                                        <span class="font-medium text-purple-600">{{ $trueque->habilidadOfrece->titulo }}</span>
                                        <span class="text-gray-400 text-xs mx-1">⇄</span>
                                        <span class="font-medium text-indigo-600">{{ $trueque->habilidadRecibe->titulo }}</span>
                                    </div>
                                    <div class="mt-1 text-sm text-gray-600">
                                        Con <a href="{{ route('perfil.show', $trueque->usuario_ofrece_id === $user->id ? $trueque->usuario_recibe_id : $trueque->usuario_ofrece_id) }}" class="text-indigo-600 hover:underline">
                                            {{ $trueque->usuario_ofrece_id === $user->id ? $trueque->usuarioRecibe->name : $trueque->usuarioOfrece->name }}
                                        </a>
                                    </div>
                                </div>
                                <a href="{{ route('trueques.show', $trueque) }}" class="btn btn-secondary text-sm">Ver detalle</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="card-title">Mis habilidades</h2>
                    @if($habilidades->isEmpty())
                        <p class="card-muted mt-2">No tienes habilidades aún. <a href="{{ route('habilidades.create') }}" class="text-indigo-600">Crea una</a>.</p>
                    @else
                        <ul class="mt-2 space-y-3">
                            @foreach($habilidades as $h)
                                <li class="border rounded-lg p-3">
                                    <a href="{{ route('habilidades.show', $h) }}" class="font-medium text-indigo-600">{{ $h->titulo }}</a>
                                    <div class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($h->descripcion, 120) }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div>
                    <h2 class="card-title">Últimas habilidades</h2>
                    @if($ultimasHabilidades->isEmpty())
                        <p class="card-muted mt-2">No hay habilidades publicadas recientemente.</p>
                    @else
                        <ul class="mt-2 space-y-3">
                            @foreach($ultimasHabilidades as $h)
                                <li class="border rounded-lg p-3">
                                    <a href="{{ route('habilidades.show', $h) }}" class="font-medium text-indigo-600">{{ $h->titulo }}</a>
                                    <div class="text-sm text-gray-600">Por {{ $h->usuario->name }} · {{ $h->categoria->nombre ?? '' }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
