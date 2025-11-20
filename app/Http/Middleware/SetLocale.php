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
        
        // Obtener idioma de la sesión, URL o fallback
        $locale = $request->segment(1);
        
        if (in_array($locale, $supportedLocales)) {
            // Si está en la URL, usarlo y guardarlo en sesión
            Session::put('locale', $locale);
            App::setLocale($locale);
        } else {
            // Si no está en URL, usar el de sesión o español por defecto
            $sessionLocale = Session::get('locale', 'es');
            App::setLocale($sessionLocale);
        }

        return $next($request);
    }
}
