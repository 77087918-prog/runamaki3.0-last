<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Railway detecta automáticamente HTTPS mediante headers
        if (app()->environment('production') && isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $request->server->set('HTTPS', 'on');
        }
        
        $response = $next($request);
        
        // En desarrollo local, configurar headers más permisivos
        if (app()->environment('local')) {
            $response->headers->set('Content-Security-Policy', 
                "default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
                "style-src 'self' 'unsafe-inline'; " .
                "img-src 'self' data: blob: *; " .
                "font-src 'self' data:; " .
                "connect-src 'self' ws: wss:; " .
                "form-action 'self';"
            );
            
            // Deshabilitar advertencias de formulario inseguro
            $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        } else {
            // Headers de seguridad más estrictos para producción Railway
            $response->headers->set('Content-Security-Policy', 
                "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
                "style-src 'self' 'unsafe-inline' https://fonts.bunny.net; " .
                "img-src 'self' data: blob: *; " .
                "font-src 'self' https://fonts.bunny.net; " .
                "connect-src 'self'; " .
                "form-action 'self';"
            );
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        
        // Headers comunes para todos los entornos
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Asegurar UTF-8 encoding
        $response->headers->set('Content-Type', 'text/html; charset=UTF-8');
        
        return $response;
    }
}