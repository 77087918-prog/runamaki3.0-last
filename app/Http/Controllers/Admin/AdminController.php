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
use App\Exports\UsuariosExport;
use App\Exports\HabilidadesExport;
use App\Exports\TruequesExport;
use App\Exports\DenunciasExport;
use Barryvdh\DomPDF\Facade\Pdf;

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
     * Eliminar habilidad
     */
    public function eliminarHabilidad($id)
    {
        $habilidad = Habilidad::findOrFail($id);
        
        // Verificar que no tenga trueques activos
        $truequesActivos = Trueque::where(function($query) use ($id) {
            $query->where('habilidad_solicitada_id', $id)
                  ->orWhere('habilidad_ofrecida_id', $id);
        })->whereIn('estado', ['pendiente', 'aceptado', 'en_proceso'])->count();

        if ($truequesActivos > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar: tiene trueques activos asociados');
        }

        $habilidad->delete();

        return redirect()->back()->with('success', 'Habilidad eliminada correctamente');
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

    /**
     * Exportar usuarios a Excel (CSV)
     */
    public function exportarUsuariosCSV(Request $request)
    {
        $filtros = $request->only(['buscar', 'estado', 'rol']);
        
        $query = User::query()->withCount('habilidades');

        if (!empty($filtros['buscar'])) {
            $query->where(function($q) use ($filtros) {
                $q->where('name', 'like', '%' . $filtros['buscar'] . '%')
                  ->orWhere('email', 'like', '%' . $filtros['buscar'] . '%');
            });
        }

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['rol'])) {
            $query->where('rol', $filtros['rol']);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->get();

        $filename = 'usuarios_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($usuarios) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            // Encabezados
            fputcsv($file, ['ID', 'Nombre', 'Email', 'Estado', 'Rol', 'Puntos', 'Habilidades', 'Valoración', 'Fecha Registro'], ';');
            
            // Datos
            foreach ($usuarios as $usuario) {
                fputcsv($file, [
                    $usuario->id,
                    $usuario->name,
                    $usuario->email,
                    ucfirst($usuario->estado),
                    ucfirst($usuario->rol),
                    $usuario->puntos,
                    $usuario->habilidades_count ?? 0,
                    number_format(0, 1),
                    $usuario->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar usuarios a PDF
     */
    public function exportarUsuariosPDF(Request $request)
    {
        $filtros = $request->only(['buscar', 'estado', 'rol']);
        
        $query = User::query()->withCount('habilidades');

        if (!empty($filtros['buscar'])) {
            $query->where(function($q) use ($filtros) {
                $q->where('name', 'like', '%' . $filtros['buscar'] . '%')
                  ->orWhere('email', 'like', '%' . $filtros['buscar'] . '%');
            });
        }

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['rol'])) {
            $query->where('rol', $filtros['rol']);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('admin.exports.usuarios_pdf', compact('usuarios'))
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download('usuarios_' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Exportar habilidades a Excel (CSV)
     */
    public function exportarHabilidadesCSV(Request $request)
    {
        $filtros = $request->only(['estado', 'categoria']);
        
        $query = Habilidad::query()->with(['usuario', 'categoria']);

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['categoria'])) {
            $query->where('categoria_id', $filtros['categoria']);
        }

        $habilidades = $query->orderBy('created_at', 'desc')->get();

        $filename = 'habilidades_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($habilidades) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            // Encabezados
            fputcsv($file, ['ID', 'Título', 'Categoría', 'Usuario', 'Email Usuario', 'Estado', 'Tipo', 'Créditos', 'Fecha Creación'], ';');
            
            // Datos
            foreach ($habilidades as $habilidad) {
                fputcsv($file, [
                    $habilidad->id,
                    $habilidad->titulo,
                    $habilidad->categoria->nombre ?? 'Sin categoría',
                    $habilidad->usuario->name,
                    $habilidad->usuario->email,
                    ucfirst($habilidad->estado),
                    ucfirst($habilidad->tipo),
                    $habilidad->creditos,
                    $habilidad->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar habilidades a PDF
     */
    public function exportarHabilidadesPDF(Request $request)
    {
        $filtros = $request->only(['estado', 'categoria']);
        
        $query = Habilidad::query()->with(['usuario', 'categoria']);

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['categoria'])) {
            $query->where('categoria_id', $filtros['categoria']);
        }

        $habilidades = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('admin.exports.habilidades_pdf', compact('habilidades'))
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download('habilidades_' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Exportar trueques a Excel (CSV)
     */
    public function exportarTruequesCSV(Request $request)
    {
        $filtros = $request->only(['estado', 'desde', 'hasta']);
        
        $query = Trueque::query()->with(['usuarioOfrece', 'usuarioRecibe', 'habilidadOfrece', 'habilidadRecibe']);

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['desde'])) {
            $query->whereDate('created_at', '>=', $filtros['desde']);
        }

        if (!empty($filtros['hasta'])) {
            $query->whereDate('created_at', '<=', $filtros['hasta']);
        }

        $trueques = $query->orderBy('created_at', 'desc')->get();

        $filename = 'trueques_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($trueques) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            // Encabezados
            fputcsv($file, ['ID', 'Usuario Ofrece', 'Usuario Recibe', 'Habilidad Ofrece', 'Habilidad Recibe', 'Estado', 'Puntos', 'Fecha Creación', 'Fecha Completado'], ';');
            
            // Datos
            foreach ($trueques as $trueque) {
                fputcsv($file, [
                    $trueque->id,
                    ($trueque->usuarioOfrece->name ?? 'N/A') . ' (' . ($trueque->usuarioOfrece->email ?? 'N/A') . ')',
                    ($trueque->usuarioRecibe->name ?? 'N/A') . ' (' . ($trueque->usuarioRecibe->email ?? 'N/A') . ')',
                    $trueque->habilidadOfrece->titulo ?? 'N/A',
                    $trueque->habilidadRecibe->titulo ?? 'N/A',
                    ucfirst($trueque->estado),
                    $trueque->puntos_intercambio ?? 0,
                    $trueque->created_at->format('d/m/Y H:i'),
                    $trueque->fecha_completado ? $trueque->fecha_completado->format('d/m/Y H:i') : 'Pendiente',
                ], ';');
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar trueques a PDF
     */
    public function exportarTruequesPDF(Request $request)
    {
        $filtros = $request->only(['estado', 'desde', 'hasta']);
        
        $query = Trueque::query()->with(['usuarioOfrece', 'usuarioRecibe', 'habilidadOfrece', 'habilidadRecibe']);

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['desde'])) {
            $query->whereDate('created_at', '>=', $filtros['desde']);
        }

        if (!empty($filtros['hasta'])) {
            $query->whereDate('created_at', '<=', $filtros['hasta']);
        }

        $trueques = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('admin.exports.trueques_pdf', compact('trueques'))
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download('trueques_' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Exportar denuncias a Excel (CSV)
     */
    public function exportarDenunciasCSV(Request $request)
    {
        $filtros = $request->only(['estado', 'tipo']);
        
        $query = Denuncia::query()->with(['denunciante', 'denunciado', 'procesadoPor']);

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }

        $denuncias = $query->orderBy('created_at', 'desc')->get();

        $filename = 'denuncias_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($denuncias) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            // Encabezados
            fputcsv($file, ['ID', 'Denunciante', 'Denunciado', 'Tipo', 'Motivo', 'Estado', 'Procesado Por', 'Comentario Admin', 'Fecha Denuncia', 'Fecha Procesamiento'], ';');
            
            // Datos
            foreach ($denuncias as $denuncia) {
                fputcsv($file, [
                    $denuncia->id,
                    $denuncia->denunciante->name . ' (' . $denuncia->denunciante->email . ')',
                    $denuncia->denunciado->name . ' (' . $denuncia->denunciado->email . ')',
                    ucfirst($denuncia->tipo),
                    $denuncia->motivo,
                    ucfirst($denuncia->estado),
                    $denuncia->procesadoPor->name ?? 'Sin procesar',
                    $denuncia->comentario_admin ?? 'N/A',
                    $denuncia->created_at->format('d/m/Y H:i'),
                    $denuncia->procesada_at ? $denuncia->procesada_at->format('d/m/Y H:i') : 'Pendiente',
                ], ';');
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar denuncias a PDF
     */
    public function exportarDenunciasPDF(Request $request)
    {
        $filtros = $request->only(['estado', 'tipo']);
        
        $query = Denuncia::query()->with(['denunciante', 'denunciado', 'procesadoPor']);

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }

        $denuncias = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('admin.exports.denuncias_pdf', compact('denuncias'))
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download('denuncias_' . now()->format('Y-m-d_His') . '.pdf');
    }
}

