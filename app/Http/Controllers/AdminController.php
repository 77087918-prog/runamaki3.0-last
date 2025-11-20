<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Habilidad;
use App\Models\Trueque;
use App\Models\Valoracion;
use App\Models\Denuncia;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Acceso denegado. Solo administradores.');
            }
            return $next($request);
        });
    }

    /**
     * Dashboard principal del admin
     */
    public function dashboard()
    {
        $stats = [
            'usuarios_total' => User::count(),
            'usuarios_activos' => User::where('estado', 'activo')->count(),
            'usuarios_nuevos_mes' => User::where('created_at', '>=', Carbon::now()->subMonth())->count(),
            'habilidades_total' => Habilidad::count(),
            'habilidades_pendientes' => Habilidad::where('estado', 'pendiente')->count(),
            'habilidades_aprobadas' => Habilidad::where('estado', 'aprobado')->count(),
            'trueques_total' => Trueque::count(),
            'trueques_activos' => Trueque::whereIn('estado', ['pendiente', 'aceptado'])->count(),
            'trueques_completados' => Trueque::where('estado', 'completado')->count(),
            'denuncias_pendientes' => Denuncia::where('estado', 'pendiente')->count(),
            'valoraciones_total' => Valoracion::count(),
            'puntos_circulacion' => User::sum('puntos_runa')
        ];

        $usuariosRecientes = User::latest()->take(5)->get();
        $habilidadesPendientes = Habilidad::where('estado', 'pendiente')
            ->with('usuario', 'categoria')
            ->latest()
            ->take(10)
            ->get();
        $denunciasPendientes = Denuncia::where('estado', 'pendiente')
            ->with('denunciante', 'denunciado')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'usuariosRecientes', 'habilidadesPendientes', 'denunciasPendientes'));
    }

    /**
     * Gestión de usuarios
     */
    public function usuarios()
    {
        $usuarios = User::with(['habilidades', 'valoracionesRecibidas'])
            ->withCount(['habilidades', 'truequesOfrecidos', 'truequesRecibidos', 'valoracionesRecibidas'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.usuarios', compact('usuarios'));
    }

    /**
     * Gestión de habilidades
     */
    public function habilidades()
    {
        $habilidades = Habilidad::with(['usuario', 'categoria'])
            ->latest()
            ->paginate(20);

        $categorias = Categoria::all();

        return view('admin.habilidades', compact('habilidades', 'categorias'));
    }

    /**
     * Aprobar habilidad
     */
    public function aprobarHabilidad(Habilidad $habilidad)
    {
        $habilidad->update(['estado' => 'aprobado']);
        
        // Dar puntos por habilidad aprobada
        $habilidad->usuario->increment('puntos_runa', 10);

        return redirect()->back()->with('success', 'Habilidad aprobada correctamente');
    }

    /**
     * Rechazar habilidad
     */
    public function rechazarHabilidad(Habilidad $habilidad, Request $request)
    {
        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);

        $habilidad->update([
            'estado' => 'rechazado',
            'motivo_rechazo' => $request->motivo
        ]);

        return redirect()->back()->with('success', 'Habilidad rechazada');
    }

    /**
     * Suspender usuario
     */
    public function suspenderUsuario(User $usuario, Request $request)
    {
        $request->validate([
            'motivo' => 'required|string|max:500',
            'dias' => 'required|integer|min:1|max:365'
        ]);

        $usuario->update([
            'estado' => 'suspendido',
            'fecha_suspension' => now(),
            'dias_suspension' => $request->dias,
            'motivo_suspension' => $request->motivo
        ]);

        return redirect()->back()->with('success', 'Usuario suspendido por ' . $request->dias . ' días');
    }

    /**
     * Reactivar usuario
     */
    public function reactivarUsuario(User $usuario)
    {
        $usuario->update([
            'estado' => 'activo',
            'fecha_suspension' => null,
            'dias_suspension' => null,
            'motivo_suspension' => null
        ]);

        return redirect()->back()->with('success', 'Usuario reactivado correctamente');
    }

    /**
     * Gestión de denuncias
     */
    public function denuncias()
    {
        $denuncias = Denuncia::with(['denunciante', 'denunciado'])
            ->latest()
            ->paginate(20);

        return view('admin.denuncias', compact('denuncias'));
    }

    /**
     * Resolver denuncia
     */
    public function resolverDenuncia(Denuncia $denuncia, Request $request)
    {
        $request->validate([
            'accion' => 'required|in:aprobar,rechazar',
            'comentario_admin' => 'nullable|string|max:1000'
        ]);

        $denuncia->update([
            'estado' => $request->accion === 'aprobar' ? 'aprobada' : 'rechazada',
            'admin_comentario' => $request->comentario_admin,
            'admin_id' => auth()->id(),
            'fecha_resolucion' => now()
        ]);

        if ($request->accion === 'aprobar') {
            // Aplicar sanción al denunciado según el tipo
            switch ($denuncia->tipo) {
                case 'contenido_inapropiado':
                    $denuncia->denunciado->decrement('puntos_runa', 20);
                    break;
                case 'spam':
                    $denuncia->denunciado->decrement('puntos_runa', 10);
                    break;
                case 'incumplimiento':
                    $denuncia->denunciado->decrement('puntos_runa', 30);
                    break;
                case 'fraude':
                    $denuncia->denunciado->update(['estado' => 'suspendido']);
                    break;
            }
        }

        return redirect()->back()->with('success', 'Denuncia resuelta correctamente');
    }

    /**
     * Estadísticas detalladas
     */
    public function estadisticas()
    {
        // Estadísticas por mes (últimos 12 meses)
        $estadisticasMeses = [];
        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $mes = $fecha->format('M Y');
            
            $estadisticasMeses[] = [
                'mes' => $mes,
                'usuarios' => User::whereMonth('created_at', $fecha->month)
                    ->whereYear('created_at', $fecha->year)->count(),
                'habilidades' => Habilidad::whereMonth('created_at', $fecha->month)
                    ->whereYear('created_at', $fecha->year)->count(),
                'trueques' => Trueque::whereMonth('created_at', $fecha->month)
                    ->whereYear('created_at', $fecha->year)->count(),
            ];
        }

        // Top categorías
        $topCategorias = Categoria::withCount('habilidades')
            ->orderBy('habilidades_count', 'desc')
            ->take(10)
            ->get();

        // Usuarios más activos
        $usuariosActivos = User::withCount(['habilidades', 'truequesOfrecidos', 'truequesRecibidos'])
            ->orderByRaw('(habilidades_count + trueques_ofrecidos_count + trueques_recibidos_count) DESC')
            ->take(10)
            ->get();

        return view('admin.estadisticas', compact('estadisticasMeses', 'topCategorias', 'usuariosActivos'));
    }

    /**
     * Configuración del sistema
     */
    public function configuracion()
    {
        $config = DB::table('configuracion')->pluck('valor', 'clave');
        
        return view('admin.configuracion', compact('config'));
    }

    /**
     * Actualizar configuración
     */
    public function actualizarConfiguracion(Request $request)
    {
        $configuraciones = $request->except('_token', '_method');
        
        foreach ($configuraciones as $clave => $valor) {
            DB::table('configuracion')->updateOrInsert(
                ['clave' => $clave],
                ['valor' => $valor, 'updated_at' => now()]
            );
        }

        return redirect()->back()->with('success', 'Configuración actualizada correctamente');
    }
}