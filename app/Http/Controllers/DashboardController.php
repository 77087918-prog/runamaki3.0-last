<?php

namespace App\Http\Controllers;

use App\Models\Habilidad;
use App\Models\Trueque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Obtener las habilidades del usuario
        $habilidades = $user->habilidades()
            ->with('categoria')
            ->latest()
            ->take(5)
            ->get();

        // Obtener las últimas habilidades publicadas por otros usuarios
        $ultimasHabilidades = Habilidad::with(['usuario', 'categoria'])
            ->where('usuario_id', '!=', $user->id)
            ->aprobadas()
            ->latest()
            ->take(6)
            ->get();

        // Estadísticas de trueques
        $truequesRecibidos = Trueque::where('usuario_recibe_id', $user->id)
            ->where('estado', 'pendiente')
            ->count();

        $truequesActivos = Trueque::where(function($query) use ($user) {
                $query->where('usuario_ofrece_id', $user->id)
                      ->orWhere('usuario_recibe_id', $user->id);
            })
            ->where('estado', 'aceptado')
            ->count();

        $truequesCompletados = Trueque::where(function($query) use ($user) {
                $query->where('usuario_ofrece_id', $user->id)
                      ->orWhere('usuario_recibe_id', $user->id);
            })
            ->where('estado', 'completado')
            ->count();

        // Últimos trueques
        $ultimosTrueques = Trueque::where(function($query) use ($user) {
                $query->where('usuario_ofrece_id', $user->id)
                      ->orWhere('usuario_recibe_id', $user->id);
            })
            ->with(['usuarioOfrece', 'usuarioRecibe', 'habilidadOfrece', 'habilidadRecibe'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'user', 
            'habilidades', 
            'ultimasHabilidades',
            'truequesRecibidos',
            'truequesActivos',
            'truequesCompletados',
            'ultimosTrueques'
        ));
    }
}