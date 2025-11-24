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
        // Configurar charset UTF-8 para la base de datos
        if (config('database.default') === 'mysql') {
            DB::statement("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
        }
        
        // Configurar longitud de índices para MySQL
        Builder::defaultStringLength(191);
        
        // Configurar locale en español
        \Carbon\Carbon::setLocale('es');
        setlocale(LC_TIME, 'es_ES.UTF-8');
    }
}
