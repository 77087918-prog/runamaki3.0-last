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
    /**
     * Verificar permisos de administrador
     */
    private function checkAdminPermissions()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Acceso denegado. Solo administradores.');
        }
        
        return null; // No hay problema de permisos
    }

    /**
     * Dashboard principal del admin
     */
    public function dashboard()
    {
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

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
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

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
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

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
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

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
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

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
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

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
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

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
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

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
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

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
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

        // Estadísticas generales
        $totalUsuarios = User::count();
        $nuevosUsuarios = User::whereMonth('created_at', now()->month)->count();
        $totalHabilidades = Habilidad::count();
        $habilidadesPendientes = Habilidad::where('estado', 'pendiente')->count();
        $totalTrueques = Trueque::count();
        $truequesCompletados = Trueque::where('estado', 'completado')->count();
        $puntosCirculacion = User::sum('puntos_runa');

        // Registros por mes (últimos 6 meses)
        $registrosPorMes = collect();
        for ($i = 5; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $mes = $fecha->format('M Y');
            $cantidad = User::whereMonth('created_at', $fecha->month)
                ->whereYear('created_at', $fecha->year)->count();
            $registrosPorMes->put($mes, $cantidad);
        }

        // Habilidades por categoría
        $habilidadesPorCategoria = DB::table('habilidades')
            ->join('categorias', 'habilidades.categoria_id', '=', 'categorias.id')
            ->select('categorias.nombre as categoria_nombre', DB::raw('count(*) as total'))
            ->groupBy('categorias.id', 'categorias.nombre')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        // Estado de trueques
        $estadoTrueques = DB::table('trueques')
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        // Usuarios más activos
        $usuariosMasActivos = User::withCount('habilidades')
            ->orderBy('puntos_runa', 'desc')
            ->take(5)
            ->get();

        // Tipos de trueque
        $tiposTrueque = DB::table('trueques')
            ->select('tipo_trueque', DB::raw('count(*) as total'))
            ->whereNotNull('tipo_trueque')
            ->groupBy('tipo_trueque')
            ->get();

        // Estado de denuncias
        $estadoDenuncias = DB::table('denuncias')
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        // Transacciones recientes
        $transaccionesRecientes = DB::table('transacciones_puntos')
            ->join('users', 'transacciones_puntos.usuario_id', '=', 'users.id')
            ->select('transacciones_puntos.*', 'users.name')
            ->orderBy('transacciones_puntos.created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.estadisticas', compact(
            'totalUsuarios', 
            'nuevosUsuarios', 
            'totalHabilidades', 
            'habilidadesPendientes',
            'totalTrueques', 
            'truequesCompletados', 
            'puntosCirculacion',
            'registrosPorMes',
            'habilidadesPorCategoria',
            'estadoTrueques',
            'usuariosMasActivos',
            'tiposTrueque',
            'estadoDenuncias',
            'transaccionesRecientes'
        ));
    }

    /**
     * Configuración del sistema
     */
    public function configuracion()
    {
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

        $config = DB::table('configuracion')->pluck('valor', 'clave');
        
        return view('admin.configuracion', compact('config'));
    }

    /**
     * Actualizar configuración
     */
    public function actualizarConfiguracion(Request $request)
    {
        if ($redirect = $this->checkAdminPermissions()) return $redirect;

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