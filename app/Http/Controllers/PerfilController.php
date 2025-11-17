<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    /**
     * Muestra el perfil del usuario autenticado (el que ha iniciado sesión)
     */
    public function index()
    {
        $user = Auth::user();// Obtiene el usuario actualmente autenticado
        // Carga relaciones importantes para mostrar en el perfil (habilidades, categorías y logros)
        $user->load(['habilidades.categoria', 'logros']);

        $habilidades = $user->habilidades;
        // Cuenta cuántos trueques ha completado el usuario (como oferente o receptor)
        $truequesCompletados = $user->truequesOfrecidos()->where('estado', 'completado')->count() 
            + $user->truequesRecibidos()->where('estado', 'completado')->count();
// Obtiene las 5 transacciones de puntos más recientes del usuario
        $transacciones = $user->transaccionesPuntos()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
// Envía toda la información a la vista del perfil
        return view('perfil.index', compact('user', 'habilidades', 'truequesCompletados', 'transacciones'));
    }
/**
     * Muestra el perfil público de otro usuario (no necesariamente el autenticado)
     */
    public function show(User $user)
    {
        $usuario = $user;// Se usa otra variable por claridad
        $usuario->load(['habilidades.categoria', 'logros']); // Carga relaciones relacionadas
 // Solo muestra las habilidades aprobadas de ese usuario
        $habilidades = $usuario->habilidades()->where('estado', 'aprobada')->get();
        // Cuenta la cantidad de trueques completados del usuario
        $truequesCompletados = $usuario->truequesOfrecidos()->where('estado', 'completado')->count() 
            + $usuario->truequesRecibidos()->where('estado', 'completado')->count();

        // Últimas valoraciones recibidas (podemos limitar para rendimiento)
        $valoracionesRecibidas = $usuario->valoracionesRecibidas()
            ->with(['evaluador', 'trueque'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        // Cuenta cuántas valoraciones totales tiene

        $totalValoraciones = $usuario->valoracionesRecibidas()->count();
  // Devuelve la vista con todos los datos del perfil del usuario
        return view('perfil.show', compact(
            'usuario',
            'habilidades',
            'truequesCompletados',
            'valoracionesRecibidas',
            'totalValoraciones'
        ));
    }

    public function editar()
    {
        // Devuelve la vista de edición con la información del usuario actual
        return view('perfil.editar', ['user' => Auth::user()]);
    }

    public function actualizar(Request $request)
    {
        $user = Auth::user();
// Valida los datos enviados desde el formulario
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Perfil actualizado correctamente');
    }

    public function cambiarPassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta']);
        }

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente');
    }

    public function transacciones()
    {
        $transacciones = Auth::user()->transaccionesPuntos()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalGanado = Auth::user()->transaccionesPuntos()
            ->where('tipo', 'ganado')
            ->sum('cantidad');

        $totalGastado = Auth::user()->transaccionesPuntos()
            ->where('tipo', 'gastado')
            ->sum('cantidad');

        return view('perfil.transacciones', compact('transacciones', 'totalGanado', 'totalGastado'));
    }
}
