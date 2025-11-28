<?php

namespace App\Http\Controllers;

use App\Models\Habilidad;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HabilidadController extends Controller
{
    /**
     * Mostrar listado de habilidades
     */
    public function index()
    {
        $habilidades = Habilidad::with(['usuario', 'categoria'])
            ->aprobadas()
            ->latest()
            ->paginate(12);

        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('habilidades.index', compact('habilidades', 'categorias'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('habilidades.create', compact('categorias'));
    }

    /**
     * Almacenar nueva habilidad
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:150',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'required|max:1000',
            'horas_ofrecidas' => 'required|integer|min:1|max:100',
            'puntos_sugeridos' => 'required|integer|min:1|max:1000',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('habilidades', 'public');
            $validated['imagen'] = $path;
        }

        $validated['usuario_id'] = Auth::id();
        $validated['estado'] = 'pendiente'; // Requiere aprobación del admin

        $habilidad = Habilidad::create($validated);

        return redirect()
            ->route('habilidades.show', $habilidad)
            ->with('success', '¡Habilidad creada! Está pendiente de aprobación por un administrador.');
    }

    /**
     * Mostrar detalle de habilidad
     */
    public function show(Habilidad $habilidad)
    {
        // Incrementar contador de visitas
        $habilidad->increment('visitas');

        // Cargar relaciones necesarias
        $habilidad->load(['usuario', 'categoria']);

        // Obtener habilidades relacionadas de la misma categoría
        $relacionadas = Habilidad::where('categoria_id', $habilidad->categoria_id)
            ->where('id', '!=', $habilidad->id)
            ->aprobadas()
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('habilidades.show', compact('habilidad', 'relacionadas'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Habilidad $habilidad)
    {
        // Verificar que el usuario sea el dueño
        if ($habilidad->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta habilidad');
        }

        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('habilidades.edit', compact('habilidad', 'categorias'));
    }

    /**
     * Actualizar habilidad
     */
    public function update(Request $request, Habilidad $habilidad)
    {
        // Verificar que el usuario sea el dueño
        if ($habilidad->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para actualizar esta habilidad');
        }

        $validated = $request->validate([
            'titulo' => 'required|max:150',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'required|max:1000',
            'horas_ofrecidas' => 'required|integer|min:1|max:100',
            'puntos_sugeridos' => 'required|integer|min:1|max:1000',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($habilidad->imagen) {
                Storage::disk('public')->delete($habilidad->imagen);
            }
            $path = $request->file('imagen')->store('habilidades', 'public');
            $validated['imagen'] = $path;
        }

        $habilidad->update($validated);

        return redirect()
            ->route('habilidades.show', $habilidad)
            ->with('success', '¡Habilidad actualizada exitosamente!');
    }

    /**
     * Eliminar habilidad
     */
    public function destroy(Habilidad $habilidad)
    {
        // Verificar que el usuario sea el dueño
        if ($habilidad->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta habilidad');
        }

        // Eliminar imagen si existe
        if ($habilidad->imagen) {
            Storage::disk('public')->delete($habilidad->imagen);
        }

        $habilidad->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Habilidad eliminada exitosamente');
    }

    /**
     * Buscar habilidades con algoritmo inteligente
     */
    public function buscar(Request $request)
    {
        $query = $request->get('q');
        $categoria = $request->get('categoria');
        $ordenPor = $request->get('orden', 'relevancia'); // relevancia, fecha, puntos, visitas

        $habilidadesQuery = Habilidad::with(['usuario', 'categoria'])
            ->aprobadas();

        // Aplicar búsqueda inteligente si hay query
        if ($query) {
            $habilidadesQuery = (new Habilidad)->smartSearch($query, ['titulo', 'descripcion']);
            $habilidadesQuery = $habilidadesQuery->aprobadas()->with(['usuario', 'categoria']);
        }

        // Filtrar por categoría
        if ($categoria) {
            $habilidadesQuery->where('categoria_id', $categoria);
        }

        // Aplicar ordenamiento
        switch ($ordenPor) {
            case 'fecha':
                $habilidadesQuery->latest();
                break;
            case 'puntos':
                $habilidadesQuery->orderBy('puntos_sugeridos', 'desc');
                break;
            case 'visitas':
                $habilidadesQuery->orderBy('visitas', 'desc');
                break;
            case 'alfabetico':
                $habilidadesQuery->orderBy('titulo', 'asc');
                break;
            default:
                // Ya se ordenó por relevancia en smartSearch
                if (!$query) {
                    $habilidadesQuery->latest();
                }
                break;
        }

        $habilidades = $habilidadesQuery
            ->paginate(12)
            ->withQueryString();

        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        // Obtener sugerencias si hay query
        $sugerencias = [];
        if ($query && strlen($query) >= 2) {
            $sugerencias = (new Habilidad)->getSearchSuggestions($query, 5);
        }

        // Estadísticas de búsqueda
        $totalResultados = $habilidades->total();
        $tiempoBusqueda = microtime(true) - LARAVEL_START;

        return view('habilidades.buscar', compact(
            'habilidades', 
            'categorias', 
            'query', 
            'categoria',
            'ordenPor',
            'sugerencias',
            'totalResultados'
        ));
    }

    /**
     * API para autocompletado
     */
    public function autocompletar(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $sugerencias = (new Habilidad)->getSearchSuggestions($query, 8);

        return response()->json($sugerencias);
    }

    /**
     * Búsqueda avanzada
     */
    public function busquedaAvanzada(Request $request)
    {
        $filtros = $request->validate([
            'titulo' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string|max:200',
            'categoria_id' => 'nullable|exists:categorias,id',
            'puntos_min' => 'nullable|integer|min:1',
            'puntos_max' => 'nullable|integer|max:1000',
            'horas_min' => 'nullable|integer|min:1',
            'horas_max' => 'nullable|integer|max:100',
            'usuario' => 'nullable|string|max:50',
            'orden' => 'nullable|in:relevancia,fecha,puntos,visitas,alfabetico'
        ]);

        $habilidades = Habilidad::with(['usuario', 'categoria'])
            ->aprobadas()
            ->when($filtros['titulo'] ?? null, function($query, $titulo) {
                return (new Habilidad)->smartSearch($titulo, ['titulo']);
            })
            ->when($filtros['descripcion'] ?? null, function($query, $descripcion) {
                return $query->where('descripcion', 'like', "%{$descripcion}%");
            })
            ->when($filtros['categoria_id'] ?? null, function($query, $categoria) {
                return $query->where('categoria_id', $categoria);
            })
            ->when($filtros['puntos_min'] ?? null, function($query, $min) {
                return $query->where('puntos_sugeridos', '>=', $min);
            })
            ->when($filtros['puntos_max'] ?? null, function($query, $max) {
                return $query->where('puntos_sugeridos', '<=', $max);
            })
            ->when($filtros['horas_min'] ?? null, function($query, $min) {
                return $query->where('horas_ofrecidas', '>=', $min);
            })
            ->when($filtros['horas_max'] ?? null, function($query, $max) {
                return $query->where('horas_ofrecidas', '<=', $max);
            })
            ->when($filtros['usuario'] ?? null, function($query, $usuario) {
                return $query->whereHas('usuario', function($q) use ($usuario) {
                    $q->where('name', 'like', "%{$usuario}%");
                });
            });

        // Aplicar ordenamiento
        $orden = $filtros['orden'] ?? 'fecha';
        switch ($orden) {
            case 'fecha':
                $habilidades->latest();
                break;
            case 'puntos':
                $habilidades->orderBy('puntos_sugeridos', 'desc');
                break;
            case 'visitas':
                $habilidades->orderBy('visitas', 'desc');
                break;
            case 'alfabetico':
                $habilidades->orderBy('titulo', 'asc');
                break;
        }

        $habilidades = $habilidades->paginate(12)->withQueryString();

        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('habilidades.busqueda-avanzada', compact(
            'habilidades',
            'categorias', 
            'filtros'
        ));
    }
}