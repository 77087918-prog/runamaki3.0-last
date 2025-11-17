<?php

namespace App\Http\Controllers;

use App\Models\Mensaje;
use App\Models\Trueque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MensajeController extends Controller
{
    public function store(Request $request, Trueque $trueque)
    {
        // Verificar que el usuario sea parte del trueque
        if ($trueque->usuario_ofrece_id !== Auth::id() && $trueque->usuario_recibe_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'mensaje' => 'required|string|max:1000'
        ]);

        $mensaje = $trueque->mensajes()->create([
            'remitente_id' => Auth::id(),
            'mensaje' => $validated['mensaje'],
            'leido' => false
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => $mensaje->load('remitente')
            ]);
        }

        return back()->with('success', 'Mensaje enviado');
    }

    public function marcarLeidos(Trueque $trueque)
    {
        $trueque->mensajes()
            ->where('remitente_id', '!=', Auth::id())
            ->where('leido', false)
            ->update(['leido' => true]);

        return response()->json(['success' => true]);
    }
}
