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
    public function create(Habilidad $habilidad = null)
    {
        $misHabilidades = Auth::user()->habilidades()
            ->where('estado', 'aprobado')
            ->get();

        // Si no tiene habilidades y quiere hacer intercambio de habilidades, lo redirige
        if ($misHabilidades->isEmpty() && request('tipo') !== 'puntos_por_habilidad') {
            return redirect()->route('habilidades.create')
                ->with('error', 'Primero debes crear una habilidad para poder hacer intercambios de habilidades');
        }

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
            'habilidad_recibe_id' => 'nullable|exists:habilidades,id',
            'tipo_trueque' => 'required|in:habilidad_por_habilidad,habilidad_por_puntos,puntos_por_habilidad',
            'puntos_ofrecidos' => 'nullable|integer|min:1',
            'mensaje_inicial' => 'nullable|string|max:1000'
        ]);

        $habilidadOfrece = Habilidad::findOrFail($validated['habilidad_ofrece_id']);
        $tipoTrueque = $validated['tipo_trueque'];

        // Validaciones básicas
        if ($habilidadOfrece->usuario_id !== Auth::id()) {
            return back()->with('error', 'La habilidad seleccionada no te pertenece');
        }

        $puntos = 0;
        $usuarioRecibe = null;

        // Lógica según el tipo de trueque
        switch ($tipoTrueque) {
            case 'habilidad_por_habilidad':
                if (!$validated['habilidad_recibe_id']) {
                    return back()->with('error', 'Debes seleccionar la habilidad que deseas recibir');
                }
                
                $habilidadRecibe = Habilidad::findOrFail($validated['habilidad_recibe_id']);
                
                if ($habilidadRecibe->usuario_id === Auth::id()) {
                    return back()->with('error', 'No puedes hacer trueque con tu propia habilidad');
                }
                
                $usuarioRecibe = $habilidadRecibe->usuario_id;
                $puntos = $this->calcularPuntosIntercambio($habilidadOfrece, $habilidadRecibe);
                break;

            case 'habilidad_por_puntos':
                if (!$validated['puntos_ofrecidos']) {
                    return back()->with('error', 'Debes especificar cuántos puntos Runa solicitas');
                }
                
                $puntos = $validated['puntos_ofrecidos'];
                // Para este caso, el usuario receptor será definido cuando alguien acepte
                $usuarioRecibe = null;
                break;

            case 'puntos_por_habilidad':
                if (!$validated['habilidad_recibe_id']) {
                    return back()->with('error', 'Debes seleccionar la habilidad que deseas recibir');
                }
                
                if (!$validated['puntos_ofrecidos']) {
                    return back()->with('error', 'Debes especificar cuántos puntos Runa ofreces');
                }

                $habilidadRecibe = Habilidad::findOrFail($validated['habilidad_recibe_id']);
                
                if ($habilidadRecibe->usuario_id === Auth::id()) {
                    return back()->with('error', 'No puedes hacer trueque con tu propia habilidad');
                }
                
                $usuarioRecibe = $habilidadRecibe->usuario_id;
                $puntos = $validated['puntos_ofrecidos'];
                
                // Verificar que el usuario tenga suficientes puntos
                if (Auth::user()->puntos_runa < $puntos) {
                    return back()->with('error', 'No tienes suficientes puntos Runa');
                }
                break;
        }

        $trueque = Trueque::create([
            'usuario_ofrece_id' => Auth::id(),
            'usuario_recibe_id' => $usuarioRecibe,
            'habilidad_ofrece_id' => $tipoTrueque === 'puntos_por_habilidad' ? null : $habilidadOfrece->id,
            'habilidad_recibe_id' => $validated['habilidad_recibe_id'],
            'tipo_trueque' => $tipoTrueque,
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

    /**
     * Calcular puntos para intercambio habilidad por habilidad
     */
    private function calcularPuntosIntercambio(Habilidad $habilidadOfrece, Habilidad $habilidadRecibe): int
    {
        // Calcular puntos del intercambio de manera más justa
        // Se basa en el promedio de ambas habilidades, pero considera las horas ofrecidas
        $puntosBase = round(($habilidadOfrece->puntos_sugeridos + $habilidadRecibe->puntos_sugeridos) / 2);
        
        // Factor de ajuste basado en las horas (más horas = más puntos)
        $horasPromedio = ($habilidadOfrece->horas_ofrecidas + $habilidadRecibe->horas_ofrecidas) / 2;
        $factorHoras = 1;
        
        if ($horasPromedio > 5) {
            $factorHoras = 1.2; // 20% más puntos para intercambios largos
        } elseif ($horasPromedio > 10) {
            $factorHoras = 1.5; // 50% más puntos para intercambios muy largos
        }
        
        return round($puntosBase * $factorHoras);
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

            // Lógica diferente según el tipo de trueque
            switch ($trueque->tipo_trueque) {
                case 'habilidad_por_habilidad':
                    // Ambos usuarios ganan puntos por enseñar sus habilidades
                    $this->procesarIntercambioHabilidades($trueque);
                    break;

                case 'habilidad_por_puntos':
                    // El que ofrece habilidad gana puntos, el que paga pierde puntos
                    $this->procesarHabilidadPorPuntos($trueque);
                    break;

                case 'puntos_por_habilidad':
                    // El que paga puntos los pierde, el que enseña los gana
                    $this->procesarPuntosPorHabilidad($trueque);
                    break;
            }
        });

        return back()->with('success', '¡Trueque completado exitosamente!');
    }

    /**
     * Procesar intercambio habilidad por habilidad
     */
    private function procesarIntercambioHabilidades(Trueque $trueque): void
    {
        // Ambos usuarios ganan puntos por enseñar sus habilidades
        
        // Usuario que ofrece gana puntos por enseñar
        TransaccionPunto::create([
            'usuario_id' => $trueque->usuario_ofrece_id,
            'tipo' => 'ganado',
            'cantidad' => $trueque->puntos_intercambio,
            'concepto' => 'Intercambio completado - Enseñaste: ' . $trueque->habilidadOfrece->titulo,
            'trueque_id' => $trueque->id
        ]);

        // Usuario que recibe también gana puntos por enseñar
        TransaccionPunto::create([
            'usuario_id' => $trueque->usuario_recibe_id,
            'tipo' => 'ganado',
            'cantidad' => $trueque->puntos_intercambio,
            'concepto' => 'Intercambio completado - Enseñaste: ' . $trueque->habilidadRecibe->titulo,
            'trueque_id' => $trueque->id
        ]);

        // Actualizar puntos de ambos usuarios
        $trueque->usuarioOfrece->increment('puntos_runa', $trueque->puntos_intercambio);
        $trueque->usuarioRecibe->increment('puntos_runa', $trueque->puntos_intercambio);
    }

    /**
     * Procesar habilidad por puntos Runa
     */
    private function procesarHabilidadPorPuntos(Trueque $trueque): void
    {
        // El que ofrece la habilidad gana los puntos
        TransaccionPunto::create([
            'usuario_id' => $trueque->usuario_ofrece_id,
            'tipo' => 'ganado',
            'cantidad' => $trueque->puntos_intercambio,
            'concepto' => 'Venta de habilidad - Enseñaste: ' . $trueque->habilidadOfrece->titulo,
            'trueque_id' => $trueque->id
        ]);

        // El que recibe la habilidad pierde los puntos
        TransaccionPunto::create([
            'usuario_id' => $trueque->usuario_recibe_id,
            'tipo' => 'gastado',
            'cantidad' => $trueque->puntos_intercambio,
            'concepto' => 'Compra de habilidad - Aprendiste: ' . $trueque->habilidadOfrece->titulo,
            'trueque_id' => $trueque->id
        ]);

        // Actualizar puntos
        $trueque->usuarioOfrece->increment('puntos_runa', $trueque->puntos_intercambio);
        $trueque->usuarioRecibe->decrement('puntos_runa', $trueque->puntos_intercambio);
    }

    /**
     * Procesar puntos por habilidad
     */
    private function procesarPuntosPorHabilidad(Trueque $trueque): void
    {
        // El que ofrece puntos los pierde
        TransaccionPunto::create([
            'usuario_id' => $trueque->usuario_ofrece_id,
            'tipo' => 'gastado',
            'cantidad' => $trueque->puntos_intercambio,
            'concepto' => 'Compra de habilidad - Aprendiste: ' . $trueque->habilidadRecibe->titulo,
            'trueque_id' => $trueque->id
        ]);

        // El que enseña la habilidad gana los puntos
        TransaccionPunto::create([
            'usuario_id' => $trueque->usuario_recibe_id,
            'tipo' => 'ganado',
            'cantidad' => $trueque->puntos_intercambio,
            'concepto' => 'Venta de habilidad - Enseñaste: ' . $trueque->habilidadRecibe->titulo,
            'trueque_id' => $trueque->id
        ]);

        // Actualizar puntos
        $trueque->usuarioOfrece->decrement('puntos_runa', $trueque->puntos_intercambio);
        $trueque->usuarioRecibe->increment('puntos_runa', $trueque->puntos_intercambio);
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
