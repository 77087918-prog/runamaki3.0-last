<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Traducciones</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Test de Traducciones - Idioma: {{ app()->getLocale() }}</h1>
        
        <!-- Selector de idioma -->
        <div class="mb-8 p-4 bg-white rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Cambiar Idioma:</h2>
            <div class="flex gap-4">
                <a href="/locale/es" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    🇪🇸 Español
                </a>
                <a href="/locale/qu" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    🏔️ Quechua
                </a>
            </div>
        </div>

        <!-- Navegación -->
        <div class="mb-8 p-4 bg-white rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Navegación:</h2>
            <div class="grid grid-cols-3 gap-4">
                <div><strong>Dashboard:</strong> {{ __('app.nav.dashboard') }}</div>
                <div><strong>Habilidades:</strong> {{ __('app.nav.skills') }}</div>
                <div><strong>Mensajes:</strong> {{ __('app.nav.messages') }}</div>
                <div><strong>Perfil:</strong> {{ __('app.nav.profile') }}</div>
                <div><strong>Admin:</strong> {{ __('app.nav.admin') }}</div>
                <div><strong>Logout:</strong> {{ __('app.nav.logout') }}</div>
            </div>
        </div>

        <!-- Dashboard -->
        <div class="mb-8 p-4 bg-white rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Dashboard:</h2>
            <div class="grid grid-cols-2 gap-4">
                <div><strong>Bienvenido:</strong> {{ __('app.dashboard.welcome') }}</div>
                <div><strong>Actividad Reciente:</strong> {{ __('app.dashboard.recent_activity') }}</div>
                <div><strong>Sin actividad:</strong> {{ __('app.dashboard.no_activity') }}</div>
                <div><strong>Acciones Rápidas:</strong> {{ __('app.dashboard.quick_actions') }}</div>
            </div>
        </div>

        <!-- Estados -->
        <div class="mb-8 p-4 bg-white rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Estados:</h2>
            <div class="grid grid-cols-3 gap-4">
                <div><strong>Activo:</strong> {{ __('app.status.active') }}</div>
                <div><strong>Pendiente:</strong> {{ __('app.status.pending') }}</div>
                <div><strong>Completado:</strong> {{ __('app.status.completed') }}</div>
                <div><strong>Aprobado:</strong> {{ __('app.status.approved') }}</div>
                <div><strong>Rechazado:</strong> {{ __('app.status.rejected') }}</div>
                <div><strong>En Progreso:</strong> {{ __('app.status.in_progress') }}</div>
            </div>
        </div>

        <!-- Botones -->
        <div class="mb-8 p-4 bg-white rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Botones:</h2>
            <div class="grid grid-cols-3 gap-4">
                <div><strong>Guardar:</strong> {{ __('app.form.save') }}</div>
                <div><strong>Cancelar:</strong> {{ __('app.form.cancel') }}</div>
                <div><strong>Editar:</strong> {{ __('app.form.edit') }}</div>
                <div><strong>Eliminar:</strong> {{ __('app.form.delete') }}</div>
                <div><strong>Buscar:</strong> {{ __('app.form.search') }}</div>
                <div><strong>Filtrar:</strong> {{ __('app.form.filter') }}</div>
            </div>
        </div>

        <!-- Auth -->
        <div class="mb-8 p-4 bg-white rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Autenticación:</h2>
            <div class="grid grid-cols-2 gap-4">
                <div><strong>Email:</strong> {{ __('auth.email') }}</div>
                <div><strong>Contraseña:</strong> {{ __('auth.password_label') }}</div>
                <div><strong>Recordarme:</strong> {{ __('auth.remember_me') }}</div>
                <div><strong>Iniciar Sesión:</strong> {{ __('auth.login_button') }}</div>
                <div><strong>¿No tienes cuenta?:</strong> {{ __('auth.no_account') }}</div>
                <div><strong>Regístrate aquí:</strong> {{ __('auth.register_here') }}</div>
            </div>
        </div>
    </div>
</body>
</html>