<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middleware para desarrollo seguro
        $middleware->web(append: [
            \App\Http\Middleware\SecureHeaders::class,
            \App\Http\Middleware\CheckUserStatus::class,
        ]);
        
        // Alias de middleware
        $middleware->alias([
            'force.https' => \App\Http\Middleware\ForceHttpsRedirect::class,
            'secure.headers' => \App\Http\Middleware\SecureHeaders::class,
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'check.status' => \App\Http\Middleware\CheckUserStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
