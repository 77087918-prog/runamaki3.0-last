<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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
        // Prioridad 1: Usuario autenticado con preferencia guardada
        if (Auth::check() && Auth::user()->locale) {
            App::setLocale(Auth::user()->locale);
            session(['locale' => Auth::user()->locale]);
        }
        // Prioridad 2: Sesión activa (para usuarios no autenticados)
        elseif (session('locale')) {
            App::setLocale(session('locale'));
        }
        // Prioridad 3: Header Accept-Language del navegador
        elseif ($request->header('Accept-Language')) {
            $browserLang = substr($request->header('Accept-Language'), 0, 2);
            $locale = in_array($browserLang, ['es', 'qu']) ? $browserLang : 'es';
            App::setLocale($locale);
            session(['locale' => $locale]);
        }
        // Por defecto: Español
        else {
            App::setLocale('es');
            session(['locale' => 'es']);
        }

        return $next($request);
    }
}
