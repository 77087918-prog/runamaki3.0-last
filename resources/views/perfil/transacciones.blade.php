@extends('layouts.app')

@section('content')
<div class="py-4 sm:py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card fade-in">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold">Historial de transacciones</h1>
                    <p class="text-gray-600 text-sm sm:text-base mt-1">Balance actual: <strong class="text-purple-600">{{ Auth::user()->puntos_runa }} Runas</strong></p>
                </div>
                <a href="{{ route('perfil.index') }}" class="btn btn-secondary text-sm sm:text-base whitespace-nowrap">← Volver al perfil</a>
            </div>

            @if($transacciones->isEmpty())
                <div class="text-center py-8 sm:py-12">
                    <div class="text-gray-400 text-4xl sm:text-5xl mb-4">💰</div>
                    <p class="text-gray-600 text-sm sm:text-base">No tienes transacciones aún.</p>
                    <p class="text-xs sm:text-sm text-gray-500 mt-2">Las transacciones aparecerán aquí cuando completes trueques.</p>
                </div>
            @else
                <!-- Vista de tabla para pantallas medianas y grandes -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transacciones as $transaccion)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaccion->created_at->format('d/m/Y') }}
                                        <div class="text-xs text-gray-500">{{ $transaccion->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="font-medium">{{ $transaccion->concepto }}</div>
                                        @if($transaccion->trueque_id)
                                            <a href="{{ route('trueques.show', $transaccion->trueque_id) }}" class="text-xs text-indigo-600 hover:underline">Ver trueque →</a>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $transaccion->cantidad > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaccion->cantidad > 0 ? '+' : '' }}{{ $transaccion->cantidad }} Runas
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaccion->tipo }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Vista de tarjetas para pantallas pequeñas -->
                <div class="md:hidden space-y-4">
                    @foreach($transacciones as $transaccion)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 text-sm">{{ $transaccion->concepto }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ $transaccion->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="text-right ml-4">
                                    <span class="text-sm font-bold {{ $transaccion->cantidad > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaccion->cantidad > 0 ? '+' : '' }}{{ $transaccion->cantidad }} Runas
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span class="bg-gray-100 px-2 py-1 rounded">{{ $transaccion->tipo }}</span>
                                @if($transaccion->trueque_id)
                                    <a href="{{ route('trueques.show', $transaccion->trueque_id) }}" class="text-indigo-600 hover:underline">Ver trueque →</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $transacciones->links() }}
                </div>

                <div class="mt-6 p-4 bg-purple-50 rounded-lg">
                    <h3 class="font-semibold text-purple-900 mb-3">Resumen</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="text-center sm:text-left">
                            <div class="text-gray-600 text-xs sm:text-sm">Total ganado</div>
                            <div class="text-lg sm:text-xl font-bold text-green-600">+{{ $totalGanado }} Runas</div>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-gray-600 text-xs sm:text-sm">Total gastado</div>
                            <div class="text-lg sm:text-xl font-bold text-red-600">-{{ abs($totalGastado) }} Runas</div>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-gray-600 text-xs sm:text-sm">Balance neto</div>
                            <div class="text-lg sm:text-xl font-bold text-purple-600">{{ $totalGanado - abs($totalGastado) }} Runas</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
