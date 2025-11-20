<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Idiomas soportados
        $supportedLocales = ['es', 'qu'];
        
        // Obtener idioma de la sesión o fallback
        $locale = Session::get('locale', 'es');
        
        // Verificar que el idioma sea válido
        if (in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
        } else {
            // Fallback a español si el idioma no es válido
            App::setLocale('es');
            Session::put('locale', 'es');
        }

        return $next($request);
    }
}
