<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HabilidadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TruequeController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\ValoracionController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\AdminController;

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    // Mostrar formulario de login (GET /)
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    // Procesar login (POST /login)
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    // Registro
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Rutas públicas de habilidades (index y buscar)
Route::get('/habilidades', [HabilidadController::class, 'index'])->name('habilidades.index');
Route::get('/habilidades/buscar', [HabilidadController::class, 'buscar'])->name('habilidades.buscar');
Route::get('/habilidades/busqueda-avanzada', [HabilidadController::class, 'busquedaAvanzada'])->name('habilidades.busqueda-avanzada');
Route::get('/habilidades/autocompletar', [HabilidadController::class, 'autocompletar'])->name('habilidades.autocompletar');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de habilidades (CRUD protegido) - PRIMERO las específicas
    Route::get('/habilidades/create', [HabilidadController::class, 'create'])->name('habilidades.create');
    Route::post('/habilidades', [HabilidadController::class, 'store'])->name('habilidades.store');
    Route::get('/habilidades/{habilidad}/edit', [HabilidadController::class, 'edit'])->name('habilidades.edit');
    Route::put('/habilidades/{habilidad}', [HabilidadController::class, 'update'])->name('habilidades.update');
    Route::delete('/habilidades/{habilidad}', [HabilidadController::class, 'destroy'])->name('habilidades.destroy');
    
    // Rutas de trueques
    Route::prefix('trueques')->name('trueques.')->group(function () {
        Route::get('/', [TruequeController::class, 'index'])->name('index');
        Route::get('/crear/{habilidad}', [TruequeController::class, 'create'])->name('create');
        Route::post('/', [TruequeController::class, 'store'])->name('store');
        Route::get('/{trueque}', [TruequeController::class, 'show'])->name('show');
        Route::post('/{trueque}/aceptar', [TruequeController::class, 'aceptar'])->name('aceptar');
        Route::post('/{trueque}/rechazar', [TruequeController::class, 'rechazar'])->name('rechazar');
        Route::post('/{trueque}/completar', [TruequeController::class, 'completar'])->name('completar');
        Route::post('/{trueque}/cancelar', [TruequeController::class, 'cancelar'])->name('cancelar');
        Route::get('/{trueque}/chat', [TruequeController::class, 'getChat'])->name('chat');
    });
    
    // Rutas de mensajes
    Route::post('/trueques/{trueque}/mensajes', [MensajeController::class, 'store'])->name('mensajes.store');
    Route::post('/trueques/{trueque}/mensajes/marcar-leidos', [MensajeController::class, 'marcarLeidos'])->name('mensajes.marcar-leidos');
    
    // Rutas de valoraciones
    Route::post('/trueques/{trueque}/valorar', [ValoracionController::class, 'store'])->name('valoraciones.store');
    
    // Rutas de perfil
    Route::prefix('perfil')->name('perfil.')->group(function () {
        Route::get('/', [PerfilController::class, 'index'])->name('index');
        Route::get('/editar', [PerfilController::class, 'editar'])->name('editar');
        Route::put('/actualizar', [PerfilController::class, 'actualizar'])->name('actualizar');
        Route::put('/cambiar-password', [PerfilController::class, 'cambiarPassword'])->name('cambiar-password');
        Route::get('/transacciones', [PerfilController::class, 'transacciones'])->name('transacciones');
        Route::get('/{user}', [PerfilController::class, 'show'])->name('show');
    });
    
    // Rutas de chat en tiempo real
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/buscar-usuarios', [ChatController::class, 'buscarUsuarios'])->name('buscar-usuarios');
        Route::post('/crear-conversacion', [ChatController::class, 'crearConversacion'])->name('crear-conversacion');
        Route::get('/{conversacionId}', [ChatController::class, 'show'])->name('show');
        Route::post('/enviar', [ChatController::class, 'store'])->name('store');
    });
});

// Rutas de administración
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Gestión de usuarios
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios');
    Route::post('/usuarios/{id}/cambiar-estado', [AdminController::class, 'cambiarEstadoUsuario'])->name('usuarios.cambiar-estado');
    Route::post('/usuarios/{id}/cambiar-rol', [AdminController::class, 'cambiarRolUsuario'])->name('usuarios.cambiar-rol');
    
    // Gestión de habilidades
    Route::get('/habilidades', [AdminController::class, 'habilidades'])->name('habilidades');
    Route::post('/habilidades/{id}/aprobar', [AdminController::class, 'aprobarHabilidad'])->name('habilidades.aprobar');
    Route::post('/habilidades/{id}/rechazar', [AdminController::class, 'rechazarHabilidad'])->name('habilidades.rechazar');
    
    // Gestión de denuncias
    Route::get('/denuncias', [AdminController::class, 'denuncias'])->name('denuncias');
    Route::post('/denuncias/{id}/procesar', [AdminController::class, 'procesarDenuncia'])->name('denuncias.procesar');
    
    // Exportaciones
    Route::get('/usuarios/exportar/csv', [AdminController::class, 'exportarUsuariosCSV'])->name('usuarios.exportar.csv');
    Route::get('/usuarios/exportar/pdf', [AdminController::class, 'exportarUsuariosPDF'])->name('usuarios.exportar.pdf');
    Route::get('/habilidades/exportar/csv', [AdminController::class, 'exportarHabilidadesCSV'])->name('habilidades.exportar.csv');
    Route::get('/habilidades/exportar/pdf', [AdminController::class, 'exportarHabilidadesPDF'])->name('habilidades.exportar.pdf');
    Route::get('/trueques/exportar/csv', [AdminController::class, 'exportarTruequesCSV'])->name('trueques.exportar.csv');
    Route::get('/trueques/exportar/pdf', [AdminController::class, 'exportarTruequesPDF'])->name('trueques.exportar.pdf');
    Route::get('/denuncias/exportar/csv', [AdminController::class, 'exportarDenunciasCSV'])->name('denuncias.exportar.csv');
    Route::get('/denuncias/exportar/pdf', [AdminController::class, 'exportarDenunciasPDF'])->name('denuncias.exportar.pdf');
});

// Ruta pública de show habilidad (DEBE IR AL FINAL para no interferir con /create)
Route::get('/habilidades/{habilidad}', [HabilidadController::class, 'show'])->name('habilidades.show');
