<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Si el usuario está baneado
            if ($user->estado === 'baneado') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->withErrors(['email' => 'Tu cuenta ha sido baneada. Contacta al administrador para más información.']);
            }
            
            // Si el usuario está suspendido
            if ($user->estado === 'suspendido') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->withErrors(['email' => 'Tu cuenta ha sido suspendida temporalmente. Contacta al administrador.']);
            }
            
            // Si el usuario está inactivo
            if ($user->estado === 'inactivo') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->withErrors(['email' => 'Tu cuenta está inactiva. Contacta al administrador para reactivarla.']);
            }
        }
        
        return $next($request);
    }
}
