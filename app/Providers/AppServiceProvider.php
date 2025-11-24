<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar longitud de índices para MySQL (siempre seguro)
        Builder::defaultStringLength(191);
        
        // Configurar locale en español (con fallback)
        try {
            \Carbon\Carbon::setLocale('es');
            if (!app()->environment('testing')) {
                setlocale(LC_TIME, ['es_ES.UTF-8', 'es_ES', 'Spanish_Spain', 'es']);
            }
        } catch (\Exception $e) {
            // Fallback silencioso
        }
        
        // Configurar charset UTF-8 solo cuando la BD esté disponible
        $this->configureCharset();
    }
    
    /**
     * Configurar charset de base de datos de manera segura
     */
    private function configureCharset(): void
    {
        // Solo si es MySQL y no estamos en build/console/testing
        if (config('database.default') !== 'mysql' || 
            app()->runningInConsole() || 
            app()->environment('testing')) {
            return;
        }
        
        // Intentar configurar charset solo si la conexión está disponible
        try {
            if (DB::connection()->getPdo()) {
                DB::statement("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
            }
        } catch (\Exception $e) {
            // Fallar silenciosamente durante build o si no hay BD
        }
    }
}
