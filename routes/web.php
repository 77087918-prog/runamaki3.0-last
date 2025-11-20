<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HabilidadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TruequeController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\ValoracionController;
use App\Http\Controllers\PerfilController;

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

// Cambio de idioma
Route::get('/locale/{locale}', [App\Http\Controllers\LocaleController::class, 'change'])->name('locale.change');

// Ruta de prueba para traducciones
Route::get('/test-translations', function () {
    return view('test-translations');
})->name('test.translations');

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
    
    // Rutas de administración
    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/usuarios', [App\Http\Controllers\AdminController::class, 'usuarios'])->name('usuarios');
        Route::get('/habilidades', [App\Http\Controllers\AdminController::class, 'habilidades'])->name('habilidades');
        Route::get('/denuncias', [App\Http\Controllers\AdminController::class, 'denuncias'])->name('denuncias');
        Route::get('/estadisticas', [App\Http\Controllers\AdminController::class, 'estadisticas'])->name('estadisticas');
        Route::get('/configuracion', [App\Http\Controllers\AdminController::class, 'configuracion'])->name('configuracion');
        
        // Acciones de administración
        Route::patch('/habilidades/{habilidad}/aprobar', [App\Http\Controllers\AdminController::class, 'aprobarHabilidad'])->name('habilidades.aprobar');
        Route::patch('/habilidades/{habilidad}/rechazar', [App\Http\Controllers\AdminController::class, 'rechazarHabilidad'])->name('habilidades.rechazar');
        Route::patch('/usuarios/{usuario}/suspender', [App\Http\Controllers\AdminController::class, 'suspenderUsuario'])->name('usuarios.suspender');
        Route::patch('/usuarios/{usuario}/reactivar', [App\Http\Controllers\AdminController::class, 'reactivarUsuario'])->name('usuarios.reactivar');
        Route::patch('/denuncias/{denuncia}/resolver', [App\Http\Controllers\AdminController::class, 'resolverDenuncia'])->name('denuncias.resolver');
        Route::patch('/denuncias/{denuncia}/marcar-revisado', [App\Http\Controllers\AdminController::class, 'marcarRevisado'])->name('denuncias.marcar-revisado');
        Route::put('/configuracion', [App\Http\Controllers\AdminController::class, 'actualizarConfiguracion'])->name('configuracion.actualizar');
    });
});

// Ruta pública de show habilidad (DEBE IR AL FINAL para no interferir con /create)
Route::get('/habilidades/{habilidad}', [HabilidadController::class, 'show'])->name('habilidades.show');
