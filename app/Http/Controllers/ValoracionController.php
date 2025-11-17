<?php

namespace App\Http\Controllers;

use App\Models\Valoracion;
use App\Models\Trueque;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValoracionController extends Controller
{
    public function store(Request $request, Trueque $trueque)
    {
        // Verificar que el trueque esté completado
        if ($trueque->estado !== 'completado') {
            return back()->with('error', 'Solo puedes valorar trueques completados');
        }

        // Verificar que el usuario sea parte del trueque
        if ($trueque->usuario_ofrece_id !== Auth::id() && $trueque->usuario_recibe_id !== Auth::id()) {
            abort(403);
        }

        // Verificar que no haya valorado ya
        $yaValoro = $trueque->valoraciones()
            ->where('evaluador_id', Auth::id())
            ->exists();

        if ($yaValoro) {
            return back()->with('error', 'Ya has valorado este trueque');
        }

        $validated = $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500'
        ]);

        // Determinar quién es el evaluado
        $evaluado_id = $trueque->usuario_ofrece_id === Auth::id() 
            ? $trueque->usuario_recibe_id 
            : $trueque->usuario_ofrece_id;

        DB::transaction(function () use ($trueque, $evaluado_id, $validated) {
            // Crear valoración
            Valoracion::create([
                'trueque_id' => $trueque->id,
                'evaluador_id' => Auth::id(),
                'evaluado_id' => $evaluado_id,
                'puntuacion' => $validated['puntuacion'],
                'comentario' => $validated['comentario'] ?? null
            ]);

            // Actualizar reputación del usuario evaluado
            $usuario = User::find($evaluado_id);
            $promedioReputacion = $usuario->valoracionesRecibidas()->avg('puntuacion');
            $usuario->update(['reputacion' => round($promedioReputacion, 2)]);
        });

        return back()->with('success', '¡Gracias por tu valoración!');
    }
}
