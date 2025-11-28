<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Habilidad;
use App\Models\Trueque;
use App\Models\Denuncia;
use App\Models\TransaccionPunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Mostrar el dashboard principal de administración
     */
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_usuarios' => User::count(),
            'usuarios_activos' => User::where('estado', 'activo')->count(),
            'usuarios_nuevos_mes' => User::whereMonth('created_at', now()->month)->count(),
            
            'total_habilidades' => Habilidad::count(),
            'habilidades_pendientes' => Habilidad::where('estado', 'pendiente')->count(),
            'habilidades_aprobadas' => Habilidad::where('estado', 'aprobado')->count(),
            
            'total_trueques' => Trueque::count(),
            'trueques_activos' => Trueque::where('estado', 'aceptado')->count(),
            'trueques_completados' => Trueque::where('estado', 'completado')->count(),
            
            'total_denuncias' => Denuncia::count(),
            'denuncias_pendientes' => Denuncia::where('estado', 'pendiente')->count(),
        ];

        // Usuarios recientes
        $usuariosRecientes = User::latest()->take(5)->get();

        // Habilidades pendientes de aprobación
        $habilidadesPendientes = Habilidad::with(['usuario', 'categoria'])
            ->where('estado', 'pendiente')
            ->latest()
            ->take(10)
            ->get();

        // Denuncias pendientes
        $denunciasPendientes = Denuncia::with(['denunciante', 'denunciado'])
            ->where('estado', 'pendiente')
            ->latest()
            ->take(5)
            ->get();

        // Actividad reciente (últimas transacciones)
        $actividadReciente = TransaccionPunto::with('usuario')
            ->latest()
            ->take(10)
            ->get();

        // Estadísticas por mes (últimos 6 meses)
        $statsGraficas = $this->getMonthlyStats();

        // Distribución de estados de usuarios
        $distribucionUsuarios = [
            'activos' => User::where('estado', 'activo')->count(),
            'suspendidos' => User::where('estado', 'suspendido')->count(),
            'baneados' => User::where('estado', 'baneado')->count(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'usuariosRecientes',
            'habilidadesPendientes',
            'denunciasPendientes',
            'actividadReciente',
            'statsGraficas',
            'distribucionUsuarios'
        ));
    }

    /**
     * Obtener estadísticas mensuales para gráficas
     */
    private function getMonthlyStats()
    {
        $meses = [];
        $usuarios = [];
        $trueques = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $meses[] = $fecha->format('M');
            
            $usuarios[] = User::whereYear('created_at', $fecha->year)
                ->whereMonth('created_at', $fecha->month)
                ->count();
                
            $trueques[] = Trueque::whereYear('created_at', $fecha->year)
                ->whereMonth('created_at', $fecha->month)
                ->count();
        }

        return [
            'meses' => $meses,
            'usuarios' => $usuarios,
            'trueques' => $trueques,
        ];
    }

    /**
     * Gestión de usuarios
     */
    public function usuarios(Request $request)
    {
        $query = User::query();

        // Filtros
        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->buscar . '%')
                  ->orWhere('email', 'like', '%' . $request->buscar . '%');
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        $usuarios = $query->withCount(['habilidades', 'truequesOfrecidos', 'truequesRecibidos'])
            ->latest()
            ->paginate(20);

        return view('admin.usuarios', compact('usuarios'));
    }

    /**
     * Gestión de habilidades
     */
    public function habilidades(Request $request)
    {
        $query = Habilidad::with(['usuario', 'categoria']);

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $query->where('titulo', 'like', '%' . $request->buscar . '%');
        }

        $habilidades = $query->latest()->paginate(20);

        return view('admin.habilidades', compact('habilidades'));
    }

    /**
     * Aprobar habilidad
     */
    public function aprobarHabilidad($id)
    {
        $habilidad = Habilidad::findOrFail($id);
        $habilidad->update(['estado' => 'aprobado']);

        return redirect()->back()->with('success', 'Habilidad aprobada correctamente');
    }

    /**
     * Rechazar habilidad
     */
    public function rechazarHabilidad($id)
    {
        $habilidad = Habilidad::findOrFail($id);
        $habilidad->update(['estado' => 'rechazado']);

        return redirect()->back()->with('success', 'Habilidad rechazada');
    }

    /**
     * Gestión de denuncias
     */
    public function denuncias(Request $request)
    {
        $query = Denuncia::with(['denunciante', 'denunciado']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $denuncias = $query->latest()->paginate(20);

        return view('admin.denuncias', compact('denuncias'));
    }

    /**
     * Procesar denuncia
     */
    public function procesarDenuncia(Request $request, $id)
    {
        $denuncia = Denuncia::findOrFail($id);
        
        $request->validate([
            'accion' => 'required|in:aprobar,rechazar',
            'comentario_admin' => 'nullable|string|max:500'
        ]);

        $denuncia->update([
            'estado' => $request->accion === 'aprobar' ? 'aprobada' : 'rechazada',
            'comentario_admin' => $request->comentario_admin,
            'procesada_por' => auth()->id(),
            'procesada_at' => now()
        ]);

        // Si se aprueba la denuncia, tomar acción contra el denunciado
        if ($request->accion === 'aprobar') {
            $denunciado = $denuncia->denunciado;
            
            // Incrementar contador de denuncias
            $denunciado->increment('denuncias_recibidas');
            
            // Si tiene muchas denuncias, suspender cuenta
            if ($denunciado->denuncias_recibidas >= 3) {
                $denunciado->update(['estado' => 'suspendido']);
            }
        }

        return redirect()->back()->with('success', 'Denuncia procesada correctamente');
    }

    /**
     * Cambiar estado de usuario
     */
    public function cambiarEstadoUsuario(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        
        $request->validate([
            'estado' => 'required|in:activo,suspendido,baneado'
        ]);

        $usuario->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', 'Estado del usuario actualizado');
    }

    /**
     * Cambiar rol de usuario
     */
    public function cambiarRolUsuario(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        
        $request->validate([
            'rol' => 'required|in:usuario,admin'
        ]);

        $usuario->update(['rol' => $request->rol]);

        return redirect()->back()->with('success', 'Rol del usuario actualizado');
    }
}

