<?php

namespace App\Http\Controllers;

use App\Models\Trueque;
use App\Models\User;
use App\Models\Habilidad;
use App\Models\TransaccionPunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TruequeController extends Controller
{
        // Muestra la lista de trueques del usuario (los que ofreció o recibió)

    public function index(Request $request)
    {
        $estado = $request->get('estado');// Filtrar por estado si se envía en la URL
        $trueques = Trueque::where(function($query) {
            // Muestra los trueques donde el usuario actual participa (ya sea ofreciendo o recibiendo)
            $query->where('usuario_ofrece_id', Auth::id())
                  ->orWhere('usuario_recibe_id', Auth::id());
        })
             // Si hay un estado filtrado, lo aplica (pendiente, aceptado, completado, etc.)
        ->when($estado, function($query, $estado) {
            return $query->where('estado', $estado);
        })
             // Carga relaciones para evitar consultas repetidas (eager loading)
        ->with(['usuarioOfrece', 'usuarioRecibe', 'habilidadOfrece', 'habilidadRecibe'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);// Muestra 10 por página

        return view('trueques.index', [
            'trueques' => $trueques,
            'filtro_estado' => $estado
        ]);
    }
// Muestra el detalle de un trueque específico
    public function show(Trueque $trueque)
    {
        // Verificar que el usuario sea parte del trueque
        if ($trueque->usuario_ofrece_id !== Auth::id() && $trueque->usuario_recibe_id !== Auth::id()) {
            abort(403, 'No tienes acceso a este trueque');
        }

        // Eager load de relaciones necesarias incluyendo valoraciones con su evaluador
        $trueque->load([
            'usuarioOfrece',
            'usuarioRecibe',
            'habilidadOfrece',
            'habilidadRecibe',
            'valoraciones.evaluador'
        ]);
        
        $mensajes = $trueque->mensajes()
            ->with('remitente')
            ->orderBy('created_at', 'asc')
            ->get();

        // Marcar mensajes como leídos
        $trueque->mensajes()
            ->where('remitente_id', '!=', Auth::id())
            ->where('leido', false)
            ->update(['leido' => true]);
 // Verifica si el usuario ya valoró este trueque
        $yaValoro = $trueque->valoraciones()
            ->where('evaluador_id', Auth::id())
            ->exists();
        // Carga las valoraciones con los evaluadores

        $valoraciones = $trueque->valoraciones()->with('evaluador')->orderBy('created_at', 'desc')->get();

        return view('trueques.detalle', [
            'trueque' => $trueque,
            'mensajes' => $mensajes,
            'es_receptor' => $trueque->usuario_recibe_id == Auth::id(),
            'ya_valoro' => $yaValoro,
            'valoraciones' => $valoraciones
        ]);
    }
    // Formulario para crear un nuevo trueque

    public function create(Habilidad $habilidad)
    {
        // Habilidad que el usuario quiere recibir
        $misHabilidades = Auth::user()->habilidades()
            ->where('estado', 'aprobado')
            ->get();
        // Si no tiene habilidades, lo redirige para que cree una primero

        if ($misHabilidades->isEmpty()) {
            return redirect()->route('habilidades.create')
                ->with('error', 'Primero debes crear una habilidad para poder hacer trueques');
        }
        // Muestra la vista con la habilidad que se quiere recibir y las que el usuario ofrece

        return view('trueques.create', [
            'habilidad_recibir' => $habilidad,
            'mis_habilidades' => $misHabilidades
        ]);
    }
    // Guarda la propuesta de trueque en la base de datos

    public function store(Request $request)
    {
                // Validar que se hayan enviado los datos requeridos

        $validated = $request->validate([
            'habilidad_ofrece_id' => 'required|exists:habilidades,id',
            'habilidad_recibe_id' => 'required|exists:habilidades,id',
            'mensaje_inicial' => 'nullable|string|max:1000'
        ]);

        $habilidadOfrece = Habilidad::findOrFail($validated['habilidad_ofrece_id']);
        $habilidadRecibe = Habilidad::findOrFail($validated['habilidad_recibe_id']);

        // Validaciones
        if ($habilidadOfrece->usuario_id !== Auth::id()) {
            return back()->with('error', 'La habilidad seleccionada no te pertenece');
        }

        if ($habilidadRecibe->usuario_id === Auth::id()) {
            return back()->with('error', 'No puedes hacer trueque con tu propia habilidad');
        }

        // Calcular puntos del intercambio (promedio).
        $puntos = round(($habilidadOfrece->puntos_sugeridos + $habilidadRecibe->puntos_sugeridos) / 2);

        $trueque = Trueque::create([
            'usuario_ofrece_id' => Auth::id(),
            'usuario_recibe_id' => $habilidadRecibe->usuario_id,
            'habilidad_ofrece_id' => $habilidadOfrece->id,
            'habilidad_recibe_id' => $habilidadRecibe->id,
            'puntos_intercambio' => $puntos,
            'estado' => 'pendiente',
        ]);

        // Mensaje inicial opcional
        if ($request->filled('mensaje_inicial')) {
            $trueque->mensajes()->create([
                'remitente_id' => Auth::id(),
                'mensaje' => $validated['mensaje_inicial']
            ]);
        }

        return redirect()->route('trueques.show', $trueque)
            ->with('success', '¡Propuesta de trueque enviada correctamente!');
    }

    public function aceptar(Trueque $trueque)
    {
        // Solo el receptor puede aceptar
        if ($trueque->usuario_recibe_id !== Auth::id()) {
            abort(403);
        }

        if ($trueque->estado !== 'pendiente') {
            return back()->with('error', 'Este trueque ya no está pendiente');
        }

        $trueque->update([
            'estado' => 'aceptado',
            'fecha_aceptacion' => now()
        ]);

        return back()->with('success', 'Trueque aceptado. ¡Coordinen para realizar el intercambio!');
    }

    public function rechazar(Trueque $trueque)
    {
        // Solo el receptor puede rechazar
        if ($trueque->usuario_recibe_id !== Auth::id()) {
            abort(403);
        }

        if ($trueque->estado !== 'pendiente') {
            return back()->with('error', 'Este trueque ya no está pendiente');
        }

        $trueque->update(['estado' => 'rechazado']);

        return back()->with('success', 'Trueque rechazado');
    }

    public function completar(Trueque $trueque)
    {
        // Ambos usuarios pueden marcar como completado
        if ($trueque->usuario_ofrece_id !== Auth::id() && $trueque->usuario_recibe_id !== Auth::id()) {
            abort(403);
        }

        if ($trueque->estado !== 'aceptado') {
            return back()->with('error', 'Solo se pueden completar trueques aceptados');
        }

        DB::transaction(function () use ($trueque) {
            $trueque->update([
                'estado' => 'completado',
                'fecha_completado' => now()
            ]);

            // Registrar transacción de puntos para el que ofreció
            TransaccionPunto::create([
                'usuario_id' => $trueque->usuario_ofrece_id,
                'tipo' => 'ganado',
                'cantidad' => $trueque->puntos_intercambio,
                'concepto' => 'Trueque completado',
                'trueque_id' => $trueque->id
            ]);

            // Actualizar puntos del usuario que ofreció
            $trueque->usuarioOfrece->increment('puntos_runa', $trueque->puntos_intercambio);
        });

        return back()->with('success', '¡Trueque completado! Los puntos Runa han sido acreditados.');
    }    

    public function cancelar(Trueque $trueque)
    {
        // Solo quien creó la propuesta puede cancelar
        if ($trueque->usuario_ofrece_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($trueque->estado, ['pendiente', 'aceptado'])) {
            return back()->with('error', 'Este trueque ya no se puede cancelar');
        }

        $trueque->update(['estado' => 'cancelado']);

        return back()->with('success', 'Trueque cancelado');
    }
}
