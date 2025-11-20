<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // En desarrollo local, no forzar HTTPS para evitar advertencias
        if (app()->environment('local')) {
            // Establecer headers para desarrollo seguro
            $response = $next($request);
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            return $response;
        }
        
        // Railway maneja HTTPS automáticamente, solo configurar headers de seguridad
        if (app()->environment('production')) {
            // Railway detecta automáticamente HTTPS, configurar app para reconocerlo
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                $request->server->set('HTTPS', 'on');
            }
            
            $response = $next($request);
            
            // Headers de seguridad para producción
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            
            return $response;
        }

        return $next($request);
    }
}
